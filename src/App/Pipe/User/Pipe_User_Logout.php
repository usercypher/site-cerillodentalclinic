<?php

class Pipe_User_Logout {
    private $app, $session;

    public function args($args) {
        list(
            $this->app,
            $this->session,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $this->session->unset('user');

        $output->redirect($this->app->urlRoute('home'));

        return array($input, $output, $success);
    }
}