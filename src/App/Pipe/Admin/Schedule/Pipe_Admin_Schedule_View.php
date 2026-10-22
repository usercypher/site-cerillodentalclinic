<?php

class Pipe_Admin_Schedule_View {
    private $app;
    private $session;
    private $scheduleRepo;
    private $userRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->scheduleRepo,
            $this->userRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $id = $input->getFrom($input->param, 'user_id', -1);
        $doctor = $this->userRepo->one('WHERE id = ?', array($id));
        if (!$doctor && $doctor['role'] !== 'doctor') {
            $success = false;
            $output->redirect($this->app->urlRoute('admin/schedule'));
            return array($input, $output, $success);
        }

        // code
        $output->content = $this->app->template($this->app->dirRoot('res/app/view/admin/schedule/view.html.php'), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'user' => $this->session->get('user'),
            'schedules' => $this->scheduleRepo->all("
                WHERE date >= CURRENT_DATE
                  AND user_id = ? 
                GROUP BY id, date
                ORDER BY date ASC
            ", array($doctor['id'])),
            'doctor' => $doctor,
        ));
        return array($input, $output, $success);
    }
}