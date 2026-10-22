<?php

class Pipe_User_RegisterView {
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

        $user = $this->session->get('register_user');

        $view = 'res/app/view/register.html.php';

        // code
        $output->content = $this->app->template($this->app->dirRoot($view), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'user_data' => $user,
        ));
        return array($input, $output, $success);
    }
}

