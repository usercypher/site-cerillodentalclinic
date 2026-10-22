<?php

class Pipe_Api_ReminderSend {
    private $app;
    private $session;
    private $gmail;
    private $messageRepo;
    private $appointmentRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->gmail,
            $this->messageRepo,
            $this->appointmentRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $appointments = $this->appointmentRepo->query("
            SELECT 
                cs.date, 
                a.id, 
                a.time_start, 
                a.time_end, 
                a.reminder_first,
                a.reminder_second,
                s.name AS service_name, 
                u.id AS user_id, 
                u.firstname, 
                u.middlename, 
                u.lastname, 
                u.email 
            FROM 
                appointment a 
            LEFT JOIN 
                schedule cs ON cs.id = a.schedule_id 
            LEFT JOIN 
                service s ON s.id = a.service_id 
            LEFT JOIN 
                user u ON u.id = a.user_id 
            WHERE 
                a.status = ? 
                AND a.type = ? 
                AND (cs.date = CURDATE() + INTERVAL 1 DAY AND a.reminder_first = 0)
                OR (cs.date = CURDATE() AND a.time_start > CURTIME() AND a.time_start < ADDTIME(CURTIME(), '02:00:00') AND a.reminder_second = 0)
            ORDER BY 
                cs.date, 
                cs.time_start
        ", array('approved', 'regular'));
        
        


        $tokenResponse = $this->gmail->getToken(
            $this->app->getEnv('OAUTH2_CLIENT_ID'),
            $this->app->getEnv('OAUTH2_CLIENT_SECRET'),
            $this->app->getEnv('OAUTH2_REFRESH_TOKEN')
        );
        $tokenData = json_decode($tokenResponse->content);
        $accessTokenExist = !empty($tokenData->access_token);
        $content = array('content' => '');

        if (!$accessTokenExist) {
            $this->app->log('error reminder-send: failed to get access token: ' . $tokenResponse->content, 'error');
            $content = array('error' => "Failed to retrieve access token.");
        }
        
        foreach ($appointments as $appointment) {
            if ($appointment['reminder_first'] == 0) {
                $this->appointmentRepo->update(array('id' => $appointment['id'], 'reminder_first' => 1));
            } elseif ($appointment['reminder_second'] == 0) {
                $this->appointmentRepo->update(array('id' => $appointment['id'], 'reminder_second' => 1));
            }
            $subject = "Reminder: Appointment - " . $appointment['service_name'];
            $body = "
Dear {$appointment['firstname']} {$appointment['middlename']} {$appointment['lastname']},

This is a reminder that you have an appointment approved for, " . date('M d, Y', strtotime($appointment['date'])) . " at " . date('h:i A', strtotime($appointment['time_start'])) . " - " . date('h:i A', strtotime($appointment['time_end'])) . " for {$appointment['service_name']}.

Please arrive 15 minutes prior to your approved time. If you have any questions or need to reschedule, please don't hesitate to contact us.

Best regards,
" . $this->app->getEnv('COMPANY_NAME') . "
";
            
            if ($accessTokenExist) {
                $retries = 0;
                $maxRetries = 3;
                $sendResult = null;
                do {
                    $sendResult = $this->gmail->send(
                        $tokenData->access_token,
                        $this->app->getEnv('COMPANY_NAME') . ' <dentalclinic010100@gmail.com>',
                        $appointment['email'],
                        $subject,
                        $body
                    );
                    if ($sendResult->code === 200) {
                        break; // success
                    }
                    usleep(pow(2, $retries) * 500000); // exponential backoff: 0.5s, 1s, 2s
                    $retries++;
                
                } while ($maxRetries > $retries && in_array($sendResult->code, array(429, 500, 503)));

                usleep(rand(300, 700) * 1000);

                if (!empty($sendResult->error) || $sendResult->code > 200) {
                    $this->app->log('error reminder-send: [curl_error] ' . $sendResult->error . ' [response_content] ' .  $sendResult->content, 'api-reminder-send');
                    $tmp = "Failed to send email to {$appointment['email']}. Please check if the email address is valid.";
                    $body .= "\n\nNote: " . $tmp;
                    $content['content'] .= $tmp . "\n";
                } else {
                    $content['content'] .= "Email sent to {$appointment['email']} successfully.\n";
                }
            }

            $this->messageRepo->insert(array(
                'subject' => $subject,
                'body' => $body,
                'receiver_id' => $appointment['user_id'],
            ));
        }

        $this->app->log('executed', 'api-reminder-send');

        $output->header['content-type'] = 'application/json';
        $output->content = json_encode($content);
    
        return array($input, $output, $success);
    }
}