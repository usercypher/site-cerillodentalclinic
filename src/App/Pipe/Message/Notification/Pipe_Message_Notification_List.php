<?php

class Pipe_Message_Notification_List {
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
        $latestMessageTime = $input->getFrom($input->frame, 'latest_message_time');

        $notifications = array();
        $view = '';
        $notifications = $this->messageRepo->all('WHERE receiver_id = ? AND sent_at > ? AND read_at IS NULL ORDER BY sent_at DESC', array($user['id'], $latestMessageTime));

        // code
        $output->header['content-type'] = 'application/json';
        $output->content = json_encode($notifications);
        return array($input, $output, $success);
    }
}