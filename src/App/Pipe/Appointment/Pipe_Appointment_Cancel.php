<?php

class Pipe_Appointment_Cancel {
    private $app;
    private $session;
    private $appointmentRepo;
    private $messageRepo;
    private $scheduleRepo;

    public function args($args) {
        // Add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->appointmentRepo,
            $this->messageRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        // Code
        $data = $input->frame;
        $route = $input->getFrom($input->query, 'redirect', 'home');
        $temp = array();

        $appointmentIds = $input->getFrom($data['appointment'], 'id', array());

        foreach ($appointmentIds as $id) {
            $temp[] = array(
                'id' => $id,
                'status' => 'cancelled',
            );
        }
        $data['appointment'] = $temp;

        if ($this->appointmentRepo->validateAndUpdateBatch($data)) {
            $user = $this->session->get('user'); // Appointee (sender)

            $appointments = $this->appointmentRepo->query("
                SELECT
                   sch.user_id,
                   sch.date,
                   a.id,
                   a.time_start,
                   a.time_end,
                   u.firstname,
                   u.middlename,
                   u.lastname
                FROM appointment a
                LEFT JOIN schedule sch ON sch.id = a.schedule_id
                LEFT JOIN user u ON u.id = sch.user_id
                WHERE a.id IN (" . rtrim(str_repeat('?,', count($appointmentIds)), ',') . ")
            ", $appointmentIds);

            $this->messageRepo->begin();

            foreach ($appointments as $a) {
                // Construct message for the doctor
                $messageBody = "Hi Dr. " . $a['lastname'] . ",\n\n" .
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
                               "Email: " . $user['email'];

                // Send the message to the doctor
                $this->messageRepo->validateAndInsert(array('message' => array(
                    'subject' => 'Appointment Cancellation Notification',
                    'sender_id' => $user['id'],
                    'receiver_id' => $a['user_id'], // Doctor's user ID
                    'body' => $messageBody,
                )));
            }
            $this->messageRepo->commit();
        }

        // Set flash message and redirect to appointment page
        $this->session->set('flash', $this->appointmentRepo->getMessages());
        $output->redirect($this->app->urlRoute($route));

        return array($input, $output, $success);
    }
}
