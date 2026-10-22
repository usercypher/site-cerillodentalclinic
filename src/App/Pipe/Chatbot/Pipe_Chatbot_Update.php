<?php

class Pipe_Chatbot_Update {
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
        $data = $input->frame;
        $route = 'admin/chatbot';
        $this->chatbotRepo->validateAndUpdate($data);
        $this->session->set('flash', $this->chatbotRepo->getMessages());
        $output->redirect($this->app->urlRoute($route));
        return array($input, $output, $success);
    }
}