<?php

class Pipe_Admin_Chatbot_index {
    private $app;
    private $session;
    private $chatbotRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->chatbotRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        // code
        $output->content = $this->app->template($this->app->dirRoot('res/app/view/admin/chatbot/index.html.php'), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'chatbots' => $this->chatbotRepo->all(),
            'user' => $this->session->get('user'),
        ));
        return array($input, $output, $success);
    }
}