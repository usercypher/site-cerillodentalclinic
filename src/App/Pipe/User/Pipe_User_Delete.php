<?php

class Pipe_User_Delete {
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

        $data['user']['id'] = $user['id'];

        $route = $input->getFrom($input->query, 'redirect', 'home');

        if ($this->userRepo->validateAndDelete($data)) {
            $route = 'user/logout';
        }

        $this->session->set('flash', $this->userRepo->getMessages());

        $output->redirect($this->app->urlRoute($route));

        return array($input, $output, $success);
    }
}