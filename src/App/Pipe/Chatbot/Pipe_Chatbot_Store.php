<?php

class Pipe_Chatbot_Store {
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
        $this->chatbotRepo->validateAndInsert($data);
        $this->session->set('flash', $this->chatbotRepo->getMessages());
        $output->redirect($this->app->urlRoute($route));
        return array($input, $output, $success);
    }
}