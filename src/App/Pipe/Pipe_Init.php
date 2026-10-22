<?php

class Pipe_Init {
    private $app, $session;

    public function args($args) {
        list(
            $this->app, 
            $this->session
        ) = $args;
    } 

    public function process($input, $output) {
        $success = true;

        $this->session->init(array('name' => 'SESSION_ID'));

        $input->data['lock_file'] = 'var/data/route_' . str_replace('/', '_', $input->route) . '.lock';
        $input->data['lock_ttl'] = 60 * 5;

        $input->data['user_role'] = array(
            'admin', 'patient', 'doctor', 'staff'
        );

        $input->data['appointment_status'] = array(
            'pending', 'approved', 'completed', 'cancelled'
        );

        $input->data['session_type'] = array(
            'morning', 'afternoon', 'evening'
        );

        return array($input, $output, $success);
    }
}