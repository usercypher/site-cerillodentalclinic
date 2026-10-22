<?php

class Pipe_Admin_Service_Index {
    private $app;
    private $session;
    private $serviceRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->serviceRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;
        
        $appiointmentStatus = array();
        foreach($input->getFrom($input->data, 'appointment_status', array()) as $status) {
            if (!in_array($status, array('completed', 'cancelled'))) $appiointmentStatus[] = $status;
        }

        // code
        $output->content = $this->app->template($this->app->dirRoot('res/app/view/admin/service/index.html.php'), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'services' => $this->serviceRepo->all(),
            'appointment_status' => $appiointmentStatus,
            'user' => $this->session->get('user'),
        ));
        return array($input, $output, $success);
    }
}