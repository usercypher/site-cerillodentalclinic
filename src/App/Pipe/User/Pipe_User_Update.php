<?php

class Pipe_User_Update {
    private $app, $session;
    private $userRepo;

    public function args($args) {
        list(
            $this->app, 
            $this->session,
            $this->userRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $data = $input->frame;

        $data['is_reset_password'] = $this->session->unset('is_reset_password');

        $user = $data['is_reset_password'] ? $this->session->get('forgot_password_user') : $this->session->get('user');

        if ($user && $user['role'] !== 'admin') {
            $data['user']['id'] = $user['id'];
            $data['user']['role'] = $user['role'];
        }

        $route = $input->getFrom($input->query, 'redirect', 'home');

        if ($data['is_reset_password']) {
            if ($this->userRepo->validateAndUpdate($data)) {
                $route = 'home';
                $this->session->unset('otp_token');
                $this->session->unset('forgot_password_user');
            }
        } else {
            $result = $this->userRepo->validateAndUpdate($data);
            if ($result && isset($user) && $user['id'] == $data['user']['id']) {
                foreach ($input->getFrom($input->frame, 'user', array()) as $k => $v) {
                    $user[$k] = $v;
                }
                $this->session->set('user', $user);
            }
        }

        $this->session->set('flash', $this->userRepo->getMessages());

        $output->redirect($this->app->urlRoute($route));

        return array($input, $output, $success);
    }
}