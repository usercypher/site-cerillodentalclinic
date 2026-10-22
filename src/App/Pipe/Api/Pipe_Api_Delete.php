<?php

class Pipe_Api_Delete {
    private $app;
    private $session;
    private $apiRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->apiRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        // code
        $data = $input->frame;

        $route = 'admin/api';

        $this->apiRepo->validateAndDeleteBatch($data);

        $this->session->set('flash', $this->apiRepo->getMessages());

        $output->redirect($this->app->urlRoute($route));

        return array($input, $output, $success);
    }
}