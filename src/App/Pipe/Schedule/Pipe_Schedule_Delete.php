<?php

class Pipe_Schedule_Delete {
    private $app;
    private $session;
    private $scheduleRepo;
    private $appointmentRepo;
    private $messageRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->scheduleRepo,
            $this->appointmentRepo,
            $this->messageRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        // code
        $data = $input->frame;

        $appointments = !empty($data['schedule']['id']) ? $this->appointmentRepo->query("
            SELECT
               sch.date,
               a.id,
               a.time_start,
               a.time_end,
               a.user_id,
               u.firstname,
               u.middlename,
               u.lastname
            FROM appointment a
            LEFT JOIN schedule sch ON sch.id = a.schedule_id
            LEFT JOIN user u ON u.id = sch.user_id
            WHERE status != 'cancelled' AND schedule_id IN (" . implode(', ', array_fill(0, count($data['schedule']['id']), '?')) . ")
        ", $data['schedule']['id']) : [];
        

        $temp = array();
        $user = $this->session->get('user');
        $route = $input->getFrom($input->query, 'redirect', 'home');
        $this->messageRepo->begin();
        foreach ($appointments as $a) {
            $this->messageRepo->validateAndInsert(array(
                'message' => array(
                    'subject' => 'Schedule adjustments',
                    'type' => 'cancellation', 
                    'sender_id' => $user['id'],
                    'receiver_id' => $a['user_id'],
                    'body' => 
                        "Just a quick update: One of your appointments has been cancelled. Here are the details:\n\n" .
                        "Cancelled Appointment:\n" .
                        "- Appointment No.: " . $a['id'] . "\n" .
                        "- Date: " . date('M d, Y', strtotime($a['date'])) . "\n" .
                        "- Time: " . date('h:i A', strtotime($a['time_start'])) . " - " . date('h:i A', strtotime($a['time_end'])) . "\n\n" .
                        "- Reason for cancellation:\n" . $data['message']['body'] . "\n\n" .
                        "If you need any more information, feel free to reach out. Thanks for your understanding!\n\n" .
                        "Best regards,\n" .
                        $user['lastname'] . ", " . $user['firstname'] . " " . $user['middlename'] . "\n" . 
                        "Contact: " . $user['contact'] . "\n" . 
                        "Email: " . $user['email']
                )
            ));
            $messageId = $this->messageRepo->lastInsertId();
            $temp[] = array(
                'id' => $a['id'],
                'status' => 'cancelled',
            );
        }
        $this->messageRepo->commit();

        $data['appointment'] = $temp;
        if ($data['appointment']) $this->appointmentRepo->validateAndUpdateBatch($data);

        $this->scheduleRepo->validateAndDeleteBatch($data);

        $this->session->set('flash', $this->scheduleRepo->getMessages());

        $output->redirect($this->app->urlRoute($route));

        return array($input, $output, $success);
    }
}