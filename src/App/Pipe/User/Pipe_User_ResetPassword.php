<?php

class Pipe_User_ResetPassword {
    private $app;
    private $session;
    private $userRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->userRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $user = $this->session->get('forgot_password_user');

        $this->session->set('is_reset_password', true);

        $view = 'res/app/view/reset-password.html.php';

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

