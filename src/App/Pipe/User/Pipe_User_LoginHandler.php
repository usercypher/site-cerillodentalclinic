<?php

class Pipe_User_LoginHandler {
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
        
        $route = trim($input->getFrom($input->query, 'redirect', 'home'), '/');

        $user = $this->userRepo->login($data);

        if ($user) {
            $this->session->set('user', $user);
        }

        $this->session->set('flash', $this->userRepo->getMessages());

        $output->redirect($this->app->urlRoute($route));

        return array($input, $output, $success);
    }
}