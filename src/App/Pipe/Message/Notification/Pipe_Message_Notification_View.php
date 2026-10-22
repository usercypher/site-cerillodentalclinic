<?php

class Pipe_Message_Notification_View {
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

        $notificationId = $input->getFrom($input->param, 'id');

        $user = $this->session->get('user');

        $view = 'res/app/view/notification/view.html.php';

        $this->messageRepo->update(array(
            'id' => $notificationId,
            'read_at' => date('Y-m-d H:i:s'),
        ));

        // code
        $output->content = $this->app->template($this->app->dirRoot($view), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'notification' => $this->messageRepo->one('WHERE id = ?', array($notificationId)),
            'user' => $this->session->get('user'),
        ));
        return array($input, $output, $success);
    }
}