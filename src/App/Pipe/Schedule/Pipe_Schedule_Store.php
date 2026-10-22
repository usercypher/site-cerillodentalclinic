<?php

class Pipe_Schedule_Store {
    private $app;
    private $session;
    private $scheduleRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->scheduleRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        // code
        $data = $input->frame;
        $route = $input->getFrom($input->query, 'redirect', 'home');
        $this->scheduleRepo->validateAndInsert($data);
        $this->session->set('flash', $this->scheduleRepo->getMessages());
        $output->redirect($this->app->urlRoute($route));
        return array($input, $output, $success);
    }
}