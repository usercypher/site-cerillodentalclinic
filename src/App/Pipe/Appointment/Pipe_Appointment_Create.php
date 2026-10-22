<?php

class Pipe_Appointment_Create {
    private $app;
    private $session;
    private $serviceRepo, $scheduleRepo, $appointmentRepo, $userRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->appointmentRepo,
            $this->serviceRepo,
            $this->scheduleRepo,
            $this->userRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $users = $this->userRepo->all('WHERE role = ?', array('doctor'));
        $user = $this->session->get('user');
        

        // --- FETCH ALL SERVICES ---
        $services = $this->serviceRepo->all();
        if (empty($services)) {
            $this->serviceRepo->addMessage('error', 'No services found.');
            $this->session->set('flash', $this->serviceRepo->getMessages());
            $success = false;
            $output->redirect($this->app->urlRoute('appointment'));
            return array($input, $output, $success);
        }

        // --- FETCH FUTURE SCHEDULES ---
        $schedules = $this->scheduleRepo->all('WHERE date > CURDATE() ORDER BY date ASC', array());
        $scheduleIds = array_column($schedules, 'id');

        if (empty($scheduleIds)) {
            $this->scheduleRepo->addMessage('error', 'No future schedules.');
            $this->session->set('flash', $this->scheduleRepo->getMessages());
            $success = false;
            $output->redirect($this->app->urlRoute('appointment'));
            return array($input, $output, $success);
        }

        // --- FETCH APPOINTMENTS ---
        $placeholders = rtrim(str_repeat('?,', count($scheduleIds)), ',');
        $appointments = $this->appointmentRepo->query("
            SELECT
                a.id,
                a.type,
                a.time_start,
                a.time_end,
                a.status,
                a.schedule_id,
                a.user_id,
                a.service_id,
                sch.date
            FROM appointment a
            LEFT JOIN schedule sch ON sch.id = a.schedule_id
            WHERE schedule_id IN (" . $placeholders . ") AND status != 'cancelled'
        ", $scheduleIds);

        $bookedBySchedule = [];
        $bookedByDateByUser = [];
        foreach ($appointments as $a) {
            if ($a['user_id'] == $user['id']) {
                $bookedByDateByUser[$a['date']][] = $a;
            }
            $bookedBySchedule[$a['schedule_id']][] = $a;
        }

        // --- GROUP SLOTS BY SERVICE & DATE ---
        $allSlots = []; // $allSlots[service_name][date] = slots[]
        
        foreach ($services as $service) {
            $serviceName = $service['name'];
            $serviceId = $service['id'];
            $serviceDuration = $service['duration'];
            $servicePrice = $service['price'];
            $serviceAppointmentStatusDefault = $service['appointment_status_default'];

            $date = '';
            foreach ($schedules as $schedule) {
                $date = $schedule['date'];
                $scheduleId = $schedule['id'];
                $doctor = array();
                foreach ($users as $user) {
                    if ($user['id'] == $schedule['user_id']) {
                        $doctor = $user;
                        break;
                    }
                }

                $booked1 = $bookedBySchedule[$schedule['id']] ?? [];
                $booked2 = $bookedByDateByUser[$schedule['date']] ?? [];
                $booked = array_merge($booked1, $booked2);
                $slots = $this->generateSlots($schedule['time_start'], $schedule['time_end'], $serviceDuration, $booked);

                foreach ($slots as $slot) {
                    $result = $this->isSlotAvailable($slot, $booked);
                    if ($result['isAvailable']) {
                        $allSlots[$serviceName][$date][] = [
                            'schedule_id' => $scheduleId,
                            'service_id' => $serviceId,
                            'service_duration' => $serviceDuration,
                            'service_price' => $servicePrice,
                            'status' => $serviceAppointmentStatusDefault,
                            'start' => $slot['start'],
                            'end' => $slot['end'],
                            'doctor_id' => $doctor['id'],
                            'doctor_name' => $doctor['lastname'] . ', ' . $doctor['firstname'] . ' ' . $doctor['middlename'],
                        ];
                    }
                }
                if (isset($allSlots[$serviceName][$date]))
                usort($allSlots[$serviceName][$date], function($a, $b) {
                    return $this->timeToMinutes($a['start']) - $this->timeToMinutes($b['start']);
                });
            }
            
        }
        

        if (empty($allSlots)) {
            $this->scheduleRepo->addMessage('error', 'No available slots.');
            $this->session->set('flash', $this->scheduleRepo->getMessages());
            $success = false;
            $output->redirect($this->app->urlRoute('appointment'));
            return array($input, $output, $success);
        }

        $output->content = $this->app->template($this->app->dirRoot('res/app/view/appointment/create.html.php'), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'user' => $this->session->get('user'),
            'allSlots' => $allSlots,
        ));
        return array($input, $output, $success);
    }
    
    private function generateSlots($start, $end, $duration, $booked) {
        $slots = [];
        $startTime = strtotime($start);
        $endTime = strtotime($end);
    
        while ($endTime >= $startTime + $duration * 60) {
            $slotStart = date('H:i', $startTime);
            $slotEnd = date('H:i', $startTime + $duration * 60);
            $slot = ['start' => $slotStart, 'end' => $slotEnd];
    
            $result = $this->isSlotAvailable($slot, $booked);
            if ($result['isAvailable']) {
                $slots[] = $slot;
                $startTime += $duration * 60;
            } else {
                $startTime += $result['new_time_start'];
            }
        }
    
        return $slots;
    }

    private function timeToMinutes($time) {
        list($h, $m) = explode(':', $time);
        return (int)$h * 60 + (int)$m;
    }

    private function isSlotAvailable($slot, $booked) {
        $slotStart = $this->timeToMinutes($slot['start']);
        $slotEnd = $this->timeToMinutes($slot['end']);
    
        foreach ($booked as $b) {
            $bookedStart = $this->timeToMinutes($b['time_start']);
            $bookedEnd = $this->timeToMinutes($b['time_end']);
    
            if ($slotEnd > $bookedStart && $bookedEnd > $slotStart) {
                return array('isAvailable' => false, 'new_time_start' => $bookedEnd - $slotStart);
            }
        }
    
        return array('isAvailable' => true);
    }

}
