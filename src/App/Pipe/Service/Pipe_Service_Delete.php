<?php

class Pipe_Service_Delete {
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

        // code
        $data = $input->frame;

        $route = 'admin/service';

        $this->serviceRepo->validateAndDeleteBatch($data);

        $this->session->set('flash', $this->serviceRepo->getMessages());

        $output->redirect($this->app->urlRoute($route));

        return array($input, $output, $success);
    }
}