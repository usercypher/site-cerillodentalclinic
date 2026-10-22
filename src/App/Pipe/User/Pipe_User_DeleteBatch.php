<?php

class Pipe_User_DeleteBatch {
    private $app;
    private $session;
    private $userRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->userRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        // code
        $data = $input->frame;

        $route = 'admin/user';

        $this->userRepo->validateAndDeleteBatch($data);

        $this->session->set('flash', $this->userRepo->getMessages());

        $output->redirect($this->app->urlRoute($route));

        return array($input, $output, $success);
    }
}