<?php

class Pipe_Appointment_Update {
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

        // code
        $data = $input->frame;
        $user = $this->session->get('user');
        $route = isset($input->param['redirect']) ? implode('/', $input->param['redirect']) : 'appointment';
        if ($this->appointmentRepo->validateAndUpdate($data) && $data['appointment']['status'] !== 'completed') {
            $a = $this->appointmentRepo->query("
                SELECT
                   sch.date,
                   a.id,
                   a.time_start,
                   a.time_end,
                   a.user_id,
                   a.status,
                   u.firstname,
                   u.middlename,
                   u.lastname
                FROM appointment a
                LEFT JOIN schedule sch ON sch.id = a.schedule_id
                LEFT JOIN user u ON u.id = sch.user_id
                WHERE a.id = ?
            ", array($data['appointment']['id']));
            $a = $a[0];
            $this->messageRepo->validateAndInsert(array(
                'message' => array(
                    'subject' => 'Appointment Status Update',
                    'sender_id' => $user['id'],
                    'receiver_id' => $a['user_id'],
                    'body' => 
                        "Hello,\n\n" .
                        "We would like to inform you that the status of your appointment has been updated. Please find the updated details below:\n\n" .
                        "Appointment Information:\n" .
                        "- Appointment No.: " . $a['id'] . "\n" .
                        "- Date: " . date('M d, Y', strtotime($a['date'])) . "\n" .
                        "- Time: " . date('h:i A', strtotime($a['time_start'])) . " - " . date('h:i A', strtotime($a['time_end'])) . "\n" .
                        "- Updated Status: " . $a['status'] . "\n" .
                        "- Reason:\n" . ($data['message']['body'] == '' ? 'N/A' : $data['message']['body'] ). "\n\n" .
                        "If you have any questions or need further assistance, feel free to get in touch.\n\n" .
                        "Best regards,\n" .
                        $user['lastname'] . ", " . $user['firstname'] . " " . $user['middlename'] . "\n" . 
                        "Contact: " . $user['contact'] . "\n" . 
                        "Email: " . $user['email']
                )
            ));
        }
        $this->session->set('flash', $this->appointmentRepo->getMessages());
        $output->redirect($this->app->urlRoute($route));
        return array($input, $output, $success);
    }
}