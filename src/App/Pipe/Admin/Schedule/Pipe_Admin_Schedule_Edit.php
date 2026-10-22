<?php

class Pipe_Admin_Schedule_Edit {
    private $app;
    private $session;
    private $scheduleRepo;
    private $appointmentRepo;
    private $userRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->scheduleRepo,
            $this->appointmentRepo,
            $this->userRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;
        
        $scheduleId = $input->getFrom($input->param, 'schedule_id');
        $doctorId = $input->getFrom($input->param, 'user_id');
        
        if (!$this->scheduleRepo->exists("WHERE id = ? AND user_id = ?", array($scheduleId, $doctorId))) {
            $success = false;
            $output->redirect($this->app->urlRoute('admin/schedule/:user_id', array(':user_id' => $doctorId)));
            return array($input, $output, $success);
        }

        $schedule = $this->scheduleRepo->one('WHERE id = ?', array($scheduleId));
        $appointments = $this->appointmentRepo->query("
            SELECT 
                a.id,
                a.type,
                a.time_start,
                a.time_end,
                a.status,
                a.user_id AS patient_id,
                u.lastname AS patient_lastname,
                u.middlename AS patient_middlename,
                u.firstname AS patient_firstname
            FROM appointment a
            LEFT JOIN user u ON u.id = a.user_id
            WHERE schedule_id = ?
        ", array($scheduleId));

        // code
        $output->content = $this->app->template($this->app->dirRoot('res/app/view/admin/schedule/edit.html.php'), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'user' => $this->session->get('user'),
            'schedule' => $schedule,
            'slots' => $this->generateScheduleBlocks($schedule['time_start'], $schedule['time_end'], $appointments),
            'doctor' => $this->userRepo->one('WHERE id = ?', array($doctorId)),
        ));
        return array($input, $output, $success);
    }

    function generateScheduleBlocks($scheduleStartTime, $scheduleEndTime, $appointments) {
        $scheduleStart = strtotime($scheduleStartTime);
        $scheduleEnd = strtotime($scheduleEndTime);
    
        // Convert appointment time strings to timestamps
        for ($i = 0; count($appointments) > $i; $i++) {
            $appointments[$i]['time_start'] = strtotime($appointments[$i]['time_start']);
            $appointments[$i]['time_end'] = strtotime($appointments[$i]['time_end']);
        }
    
        // Sort appointments by start time (manually, since closures are not supported)
        usort($appointments, array($this, 'compareAppointments'));
    
        $blocks = array();
        $current = $scheduleStart;
    
        for ($i = 0; count($appointments) > $i; $i++) {
            $appt = $appointments[$i];
    
            // Skip irrelevant types
            if ($appt['status'] == 'cancelled') {
                continue;
            }
    
            // Add open slot if there's a gap
            if ($appt['time_start'] > $current) {
                $blocks[] = array(
                    'type' => 'open slot',
                    'time_start' => date("H:i", $current),
                    'time_end' => date("H:i", $appt['time_start'])
                );
            }
    
            // Add the actual appointment
            $blocks[] = array(
                'id' => $appt['id'],
                'type' => $appt['type'],
                'patient_id' => $appt['patient_id'],
                'patient_firstname' => $appt['patient_firstname'],
                'patient_middlename' => $appt['patient_middlename'],
                'patient_lastname' => $appt['patient_lastname'],
                'time_start' => date("H:i", $appt['time_start']),
                'time_end' => date("H:i", $appt['time_end'])
            );
    
            // Advance current time
            if ($appt['time_end'] > $current) {
                $current = $appt['time_end'];
            }
        }
    
        // Final open slot after last appointment
        if ($scheduleEnd > $current) {
            $blocks[] = array(
                'type' => 'open slot',
                'time_start' => date("H:i", $current),
                'time_end' => date("H:i", $scheduleEnd)
            );
        }
    
        return $blocks;
    }

    // PHP 4-compatible compare function for usort
    function compareAppointments($a, $b) {
        if ($a['time_start'] == $b['time_start']) return 0;
        return ($b['time_start'] > $a['time_start']) ? -1 : 1;
    }

}