<?php

class Pipe_Appointment_Index {
    private $app;
    private $session;
    private $scheduleRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->scheduleRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $data = $input->query;
        $toDate = "";
        if (!empty($data['to-date'])){
            $toDate = $data['to-date'];
        } else {
            $schedule = $this->scheduleRepo->one("WHERE date > NOW() ORDER BY date DESC");
            if ($schedule) {
                $toDate = $schedule['date'];
            } else {
                $toDate = date('Y-m-d');
            }
        }

        // code
        $output->content = $this->app->template($this->app->dirRoot('res/app/view/appointment/index.html.php'), array(
            'app' => $this->app,
            'output' => $output,
            'user' => $this->session->get('user'),
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'search' => isset($data['search']) ? $data['search'] : '',
            'status' => isset($data['status']) ? $data['status'] : '',
            'from_date' => !empty($data['from-date']) ? $data['from-date'] : date('Y-m-d'),
            'to_date' => $toDate,
            'appointment_status' => $input->data['appointment_status'],
        ));
        return array($input, $output, $success);
    }
}