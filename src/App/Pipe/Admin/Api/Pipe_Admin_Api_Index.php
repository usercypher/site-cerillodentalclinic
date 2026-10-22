<?php

class Pipe_Admin_Api_index {
    private $app;
    private $session;
    private $apiRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->apiRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;
        
        $user = $this->session->get('user');

        // code
        $output->content = $this->app->template($this->app->dirRoot('res/app/view/admin/api/index.html.php'), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'apis' => $this->apiRepo->all('WHERE user_id = ?', array($user['id'])),
            'user' => $user,
        ));
        return array($input, $output, $success);
    }
}