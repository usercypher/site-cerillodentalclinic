<?php

class Pipe_User_Profile {
    private $app;
    private $session;
    private $userRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->userRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $user = $this->session->get('user');

        $userId = $input->getFrom($input->param, 'id', (isset($user['id']) ? $user['id'] : null));
        $userData = null;
        if ($userId) {
            $userData = $this->userRepo->one('WHERE id = ?', array($userId));
        }

        $view = 'res/app/view/profile/view.html.php';


        // code
        $output->content = $this->app->template($this->app->dirRoot($view), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'user' => $user,
            'user_data' => $userData,
        ));
        return array($input, $output, $success);
    }
}