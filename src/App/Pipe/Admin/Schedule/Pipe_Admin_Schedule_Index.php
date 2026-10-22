<?php

class Pipe_Admin_Schedule_Index {
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

        // code
        $output->content = $this->app->template($this->app->dirRoot('res/app/view/admin/schedule/index.html.php'), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'user' => $this->session->get('user'),
            'users' => $this->userRepo->all("
                WHERE role = ?
            ", array('doctor')),
        ));
        return array($input, $output, $success);
    }
}