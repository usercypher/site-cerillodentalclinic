<?php

class Pipe_Message_Delete {
    private $app;
    private $session;
    private $messageRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->messageRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        // code
        $data = $input->frame;

        $user = $this->session->get('user');

        $route = $route = $input->getFrom($input->query, 'redirect', 'home');

        $this->messageRepo->validateAndDeleteBatch($data);

        $this->session->set('flash', $this->messageRepo->getMessages());

        $output->redirect($this->app->urlRoute($route));

        return array($input, $output, $success);
    }
}