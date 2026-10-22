<?php

class Pipe_User_Store {
    private $app, $session;
    private $userRepo;

    public function args($args) {
        list(
            $this->app, 
            $this->session,
            $this->userRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $data = $input->frame;

        $user = $this->session->get('user');

        if (!($user && $user['role'] === 'admin')) {
            $data['user']['role'] = 'patient';
        }
        
        if ($this->userRepo->validateAndInsert($data) && !$user) {
            $this->session->set('user', $this->userRepo->login($data));
        }

        $route = $input->getFrom($input->query, 'redirect', 'home');

        $this->session->set('flash', $this->userRepo->getMessages());

        $output->redirect($this->app->urlRoute($route));

        return array($input, $output, $success);
    }
}