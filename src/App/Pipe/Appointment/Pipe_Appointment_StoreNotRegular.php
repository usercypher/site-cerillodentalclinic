<?php

class Pipe_Appointment_StoreNotRegular {
    private $app;
    private $session;
    private $appointmentRepo;
    private $messageRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->appointmentRepo,
            $this->messageRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        // code
        $data = $input->frame;
        
        $booked = $this->appointmentRepo->query("
            SELECT
               sch.date,
               a.id,
               a.time_start,
               a.time_end,
               a.user_id,
               u.firstname,
               u.middlename,
               u.lastname
            FROM appointment a
            LEFT JOIN schedule sch ON sch.id = a.schedule_id
            LEFT JOIN user u ON u.id = sch.user_id
            WHERE schedule_id = ? AND status != ?
        ", array($data['appointment']['schedule_id'], 'cancelled'));
        $user = $this->session->get('user');
        $route = $input->getFrom($input->query, 'redirect', 'home');

        $slot = array(
            'start' => $data['appointment']['time_start'],
            'end' => $data['appointment']['time_end'],
        );
        $result = $this->isSlotAvailable($slot, $this->appointmentRepo->all('WHERE schedule_id = ? AND status != ? AND (type = ? OR type = ?)', array($data['appointment']['schedule_id'], 'cancelled', 'break', 'cancelled')));
        if ($result['isAvailable']) {
            // insert appointment
            $this->appointmentRepo->validateAndInsert($data);
            $affectedAppointments = $this->affectedAppointments($slot, $booked);
            if ($affectedAppointments) {
                $this->messageRepo->begin();
                $temp = array();
                foreach ($affectedAppointments as $a) {
                    $this->messageRepo->validateAndInsert(array(
                        'message' => array(
                            'subject' => 'Schedule Adjustments',
                            'type' => 'cancellation', 
                            'sender_id' => $user['id'],
                            'receiver_id' => $a['user_id'],
                            'body' => 
                                "Just a quick update: One of your appointments has been cancelled. Here are the details:\n\n" .
                                "Cancelled Appointment:\n" .
                                "- Appointment No.: " . $a['id'] . "\n" .
                                "- Date: " . date('M d, Y', strtotime($a['date'])) . "\n" .
                                "- Time: " . date('h:i A', strtotime($a['time_start'])) . " - " . date('h:i A', strtotime($a['time_end'])) . "\n\n" .
                                "- Reason for cancellation:\n" . $data['message']['body'] . "\n\n" .
                                "If you need any more information, feel free to reach out. Thanks for your understanding!\n\n" .
                                "Best regards,\n" .
                                $user['lastname'] . ", " . $user['firstname'] . " " . $user['middlename'] . "\n" . 
                                "Contact: " . $user['contact'] . "\n" . 
                                "Email: " . $user['email']
                        )
                    ));
                    $temp[] = array(
                        'id' => $a['id'],
                        'status' => 'cancelled',
                    );
                }
                // cancel affected appointment
                $data['appointment'] = $temp;
                if ($this->appointmentRepo->validateAndUpdateBatch($data)) {
                    $this->messageRepo->commit();
                }
            }
        } else {
            $this->appointmentRepo->addMessage('error', 'Slot not Avilable, conflict on appointment type "' . $result['slotConflict']['type'] . '" ' . $result['slotConflict']['time_start'] . ' - ' . $result['slotConflict']['time_end'] . '.');
        }
        $this->session->set('flash', $this->appointmentRepo->getMessages());
        $output->redirect($this->app->urlRoute($route));
        return array($input, $output, $success);
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
                return array('isAvailable' => false, 'new_time_start' => $bookedEnd - $slotStart, 'slotConflict' => $b);
            }
        }
    
        return array('isAvailable' => true);
    }

    private function affectedAppointments($slot, $booked) {
        $affectedAppointments = array();
        $slotStart = $this->timeToMinutes($slot['start']);
        $slotEnd = $this->timeToMinutes($slot['end']);
    
        foreach ($booked as $b) {
            $bookedStart = $this->timeToMinutes($b['time_start']);
            $bookedEnd = $this->timeToMinutes($b['time_end']);
    
            if ($slotEnd > $bookedStart && $bookedEnd > $slotStart) {
                $affectedAppointments[] = $b;
            }
        }
        return $affectedAppointments;
    }
}