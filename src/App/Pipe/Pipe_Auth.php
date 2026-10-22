<?php

class Pipe_Auth {
    public $app, $session;

    public function args($args) {
        list(
            $this->app,
            $this->session
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $app = $this->app;

        $user = $this->session->get('user');
        if (!$user) {
            $output->redirect($app->urlRoute('home?redirect=:redirect', array(
                ':redirect' => urlencode($input->route),
            )));
            $success = false;
        }

        return array($input, $output, $success);
    }
}