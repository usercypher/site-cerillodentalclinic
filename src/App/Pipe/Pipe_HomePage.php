<?php

class Pipe_HomePage {
    private $app, $session, $asset, $serviceRepo, $userRepo;

    public function args($args) {
        list(
            $this->app, 
            $this->session,
            $this->serviceRepo,
            $this->userRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $output->content = $this->app->template($this->app->dirRoot('res/app/view/home.html.php'), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'redirect' => $input->getFrom($input->query, 'redirect', ''),
            'services' => $this->serviceRepo->all(),
            'users' => $this->userRepo->all('WHERE role = ? OR role = ?', array('doctor', 'staff')),
        ));

        return array($input, $output, $success);
    }
}