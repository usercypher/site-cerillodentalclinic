<?php

class Pipe_Staff_Dashboard {
    private $app;
    private $session;
    private $messageRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->messageRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $user = $this->session->get('user');

        // code
        $output->content = $this->app->template($this->app->dirRoot('res/app/view/staff/dashboard/index.html.php'), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'user' => $this->session->get('user'),
            'notifications' => $this->messageRepo->all('WHERE receiver_id = ? AND read_at IS NULL ORDER BY sent_at DESC', array($user['id'])),
        ));
        return array($input, $output, $success);
    }
}
