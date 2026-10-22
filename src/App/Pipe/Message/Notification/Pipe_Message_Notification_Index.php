<?php

class Pipe_Message_Notification_index {
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

        $notifications = array();
        $view = 'res/app/view/notification/index.html.php';

        $notifications = $this->messageRepo->all('WHERE receiver_id = ? ORDER BY sent_at DESC', array($user['id']));

        // code
        $output->content = $this->app->template($this->app->dirRoot($view), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'notifications' => $notifications,
            'user' => $this->session->get('user'),
        ));
        return array($input, $output, $success);
    }
}