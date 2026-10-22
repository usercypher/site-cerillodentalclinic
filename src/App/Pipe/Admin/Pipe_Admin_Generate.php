<?php

class Pipe_Admin_Generate {
    private $session;
    private $userRepo;

    public function args($args) {
        list(
            $this->session,
            $this->userRepo
        ) = $args;
    }

    
    public function process($input, $output) {
        $success = true;

        $data['user'] = array(
            'firstname' => 'admin',
            'email' => 'admin@test', // it needs @
            'password' => '123',
            'role' => 'admin',
        );

        $data['confirm_password'] = $data['user']['password'];

        $result = $this->userRepo->validateAndInsert($data);

        $content = '';

        $flash = $this->userRepo->getMessages();
        foreach ($flash as $f) {
            $content .= '[' . $f['type'] . '] ' . $f['message'] . PHP_EOL;
        }

        $output->content = $content;

        return array($input, $output, $success);
    }

}