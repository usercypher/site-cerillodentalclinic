<?php

class Pipe_Admin_User_Index {
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

        // code
        $output->content = $this->app->template($this->app->dirRoot('res/app/view/admin/user/index.html.php'), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'user_role' => $input->getFrom($input->data, 'user_role'),
            'search' => isset($data['search']) ? $data['search'] : '',
            'role' => isset($data['role']) ? $data['role'] : '',
            'user' => $this->session->get('user'),
        ));
        return array($input, $output, $success);
    }
}