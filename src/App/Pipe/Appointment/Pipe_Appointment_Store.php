<?php

class Pipe_Appointment_Store {
    private $app;
    private $session;
    private $appointmentRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->appointmentRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        // code
        $data = $input->frame;
        $route = 'appointment';

        $booked = $this->appointmentRepo->all('WHERE schedule_id = ? AND status != \'cancelled\'', array($data['appointment']['schedule_id']));

        $slot = array(
            'start' => $data['appointment']['time_start'],
            'end' => $data['appointment']['time_end'],
        );

        $result = $this->isSlotAvailable($slot, $booked);
        if ($result['isAvailable']) {
            $this->appointmentRepo->validateAndInsert($data);
        } else {
            $route = 'appointment/create';
            $this->appointmentRepo->addMessage('error', 'Slot is already booked.');
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

}