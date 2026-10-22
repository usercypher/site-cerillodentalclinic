<?php

class Pipe_User_Settings {
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

        $user = $this->session->get('user');

        $view = 'res/app/view/settings/index.html.php';

        // code
        $output->content = $this->app->template($this->app->dirRoot($view), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'user' => $user,
        ));
        return array($input, $output, $success);
    }
}