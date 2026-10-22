<?php

class Pipe_User_Dashboard {
    private $app;
    private $session;
    private $appointmentRepo;
    private $messageRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->appointmentRepo,
            $this->messageRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $user = $this->session->get('user');
        $userId = $user['id'];

        // code
        $output->content = $this->app->template($this->app->dirRoot('res/app/view/dashboard/index.html.php'), array(
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
                  cs.date

                FROM appointment a 
                  LEFT JOIN schedule cs ON cs.id = a.schedule_id
                  LEFT JOIN service svc ON a.service_id = svc.id
                
                WHERE a.user_id = ?
                  AND a.type = ?
                  AND (cs.date > CURDATE() || cs.date = CURDATE())

                ORDER BY cs.date ASC;
            ", array($userId, 'regular')),
            'notifications' => $this->messageRepo->all('WHERE receiver_id = ? AND read_at IS NULL ORDER BY sent_at DESC', array($userId)),
        ));
        return array($input, $output, $success);
    }
}
