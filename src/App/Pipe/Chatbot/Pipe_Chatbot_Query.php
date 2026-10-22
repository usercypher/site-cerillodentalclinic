<?php

class Pipe_Chatbot_Query {
    private $app;
    private $session;
    private $chatbotRepo;
    private $serviceRepo;
    private $userRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->chatbotRepo,
            $this->serviceRepo,
            $this->userRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        // code

        $data = array(
            'user' => $this->userRepo->all('WHERE role = ? OR role = ?', array('doctor', 'staff'), 'id, firstname, middlename, lastname, contact, email, role'),
            'service' => $this->serviceRepo->all(),
            'chatbot' => $this->chatbotRepo->all(),
            'links' => array($this->app->urlRoute('appointment/create'), $this->app->urlRoute('profile/:id')),
        );
        $context = "You are a clinic assistant. Your role is to help the user by responding to their query in a friendly, helpful, and informative manner." . "\n\n" .
            "Guidelines:" . "\n" .
            "- Focus on addressing the user's query as effectively as possible, using the information provided in the structured data." . "\n" .
            "- Prioritize answering the question and offering the most relevant and helpful details." . "\n" .
            "- Only use links explicitly provided in the 'links' array. Do not generate new or random URLs." . "\n" .
            "- You are allowed to guess and replace placeholders in links (e.g., replace ':id' with the actual user ID if available in the data)." . "\n" .
            "- Only use HTML <a> tags if there is a valid, usable link after placeholder replacement." . "\n" .
            "- Always format your response in raw HTML, not markdown or plain text." . "\n" .
            "- Do NOT wrap HTML in backticks or code blocks. Output should be raw, browser-renderable HTML only." . "\n" .
            "- Keep the response natural and conversational, as if assisting someone in real time." . "\n\n" .
            "User Query:" . "\n" . json_encode($input->getFrom($input->frame, 'query')) . "\n\n" .
            "Structured Data:" . "\n" . json_encode($data, JSON_PRETTY_PRINT) . "\n";

        $output->header['content-type'] = 'application/json';
        $output->content = json_encode(array(
            'context' => $context,
            'key' => $this->app->getEnv('API_GEMINI_KEY'),
            'url' => $this->app->getEnv('API_GEMINI_URL'),
        ));

        return array($input, $output, $success);
    }
}