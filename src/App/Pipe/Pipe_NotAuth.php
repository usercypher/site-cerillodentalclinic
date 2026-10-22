<?php

class Pipe_NotAuth {
    private $app;
    private $session;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $app = $this->app;

        $user = $this->session->get('user');
        if ($user) {
            if ($user['role'] === 'patient') {
                $output->redirect($app->urlRoute('dashboard'));
            } else {
                $output->redirect($app->urlRoute($user['role'] . '/dashboard'));
            }
            $success = false;
        }
        return array($input, $output, $success);
    }
}