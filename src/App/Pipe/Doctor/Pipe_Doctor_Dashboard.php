<?php

class Pipe_Doctor_Dashboard {
    private $app;
    private $session;
    private $appointmentRepo;
    private $messageRepo;
    private $scheduleRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->appointmentRepo,
            $this->messageRepo,
            $this->scheduleRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $user = $this->session->get('user');

        // code
        $output->content = $this->app->template($this->app->dirRoot('res/app/view/doctor/dashboard/index.html.php'), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'user' => $this->session->get('user'),
            'appointments' => $this->appointmentRepo->query("
                SELECT 
                  a.id,
                  a.time_start,
                  a.time_end,
                  a.status,

                  svc.name AS service_name,
                  sch.date

                FROM appointment a 
                  LEFT JOIN schedule sch ON sch.id = a.schedule_id
                  LEFT JOIN user u ON u.id = sch.user_id
                  LEFT JOIN service svc ON a.service_id = svc.id
                
                WHERE a.type = ? 
                  AND (sch.date > CURDATE() || sch.date = CURDATE())
                  AND sch.user_id = ?

                ORDER BY sch.date ASC;
            ", array('regular', $user['id'])),
            'notifications' => $this->messageRepo->all('WHERE receiver_id = ? AND read_at IS NULL ORDER BY sent_at DESC', array($user['id'])),
            'schedule' => $this->scheduleRepo->all('WHERE date >= CURRENT_DATE AND user_id = ? ORDER BY date ASC', array($user['id'])),
        ));
        return array($input, $output, $success);
    }
}
