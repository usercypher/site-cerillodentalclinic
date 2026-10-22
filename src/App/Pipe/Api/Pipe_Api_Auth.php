<?php

class Pipe_Api_Auth {
    public $app;
    public $session;
    private $apiRepo;

    public function args($args) {
        list(
            $this->app,
            $this->session,
            $this->apiRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $apiKey = $input->getFrom($input->frame, 'key');

        if (!$this->apiRepo->exists('WHERE content = ?', array($apiKey))) {
            $success = false;
            $output->code = 500;
            $output->content = json_encode(array(
                'error' => 'Invalid api key.'
            ));
        }

        return array($input, $output, $success);
    }
}