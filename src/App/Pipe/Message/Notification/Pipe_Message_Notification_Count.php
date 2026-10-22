<?php

class Pipe_Message_Notification_Count {
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

        $notificationCount = 0;

        $notificationCount = $this->messageRepo->count('WHERE receiver_id = ? AND read_at IS NULL', array($user['id']));

        $output->header['content-type'] = 'text/plain';
        $output->content = $notificationCount;

        return array($input, $output, $success);
    }
}