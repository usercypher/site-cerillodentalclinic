<?php

class Pipe_Appointment_Delete {
    private $app;
    private $session;
    private $appointmentRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->appointmentRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        // code
        $data = $input->frame;
        $user = $this->session->get('user');

        $route = $input->getFrom($input->query, 'redirect', 'home');

        $this->appointmentRepo->validateAndDeleteBatch($data);

        $this->session->set('flash', $this->appointmentRepo->getMessages());

        $output->redirect($this->app->urlRoute($route));

        return array($input, $output, $success);
    }
}