<?php

class Pipe_User_OtpStoreData {
    private $app, $session;

    public function args($args) {
        list(
            $this->app, 
            $this->session,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $input->frame['user'] = $this->session->unset('register_user');
        
        unset($input->query['redirect']);
        
        $this->session->unset('otp_token');

        return array($input, $output, $success);
    }
}