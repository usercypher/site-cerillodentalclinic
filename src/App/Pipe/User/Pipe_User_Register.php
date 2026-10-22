<?php

class Pipe_User_Register {
    private $app, $session, $userRepo, $gmail;

    public function args($args) {
        list(
            $this->app, 
            $this->session,
            $this->userRepo,
            $this->gmail,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $data = $input->frame;
        
        $route = 'home';

        $user = $input->getFrom($input->frame, 'user');
        
        if ($this->userRepo->exists('WHERE email = ?', array($user['email']))) {
            $this->userRepo->addMessage('error', 'Email Already Exists.');
            $this->session->unset('otp_token');
            $success = false;
        } else {
            $this->session->set('register_user', $user);
    
            $tokenResponse = $this->gmail->getToken(
                $this->app->getEnv('OAUTH2_CLIENT_ID'),
                $this->app->getEnv('OAUTH2_CLIENT_SECRET'),
                $this->app->getEnv('OAUTH2_REFRESH_TOKEN')
            );
            $tokenData = json_decode($tokenResponse->content);
            if (!empty($tokenData->access_token)) {
                $subject = "Subject: Registration Verification Code";
                $body = "
Dear {$user['firstname']} {$user['middlename']} {$user['lastname']},

We received your request to register an account. To continue with your registration, please use the verification code below:

{$this->session->get('otp_token')}

Please enter this code on the verification page to complete your registration.

If you did not attempt to create an account, you may safely ignore this email.

If you have any questions or need assistance, please contact our support team at dentalclinic010100@gmail.com.

Best regards,
" . $this->app->getEnv('COMPANY_NAME') . "
";
                $retries = 0;
                $maxRetries = 3;
                $sendResult = null;
                do {
                    $sendResult = $this->gmail->send(
                        $tokenData->access_token,
                        $this->app->getEnv('COMPANY_NAME') . ' <dentalclinic010100@gmail.com>',
                        $user['email'],
                        $subject,
                        $body
                    );
                    if ($sendResult->code === 200) {
                        break; // success
                    }
                    usleep(pow(2, $retries) * 500000); // exponential backoff: 0.5s, 1s, 2s
                    $retries++;
                
                } while ($maxRetries > $retries && in_array($sendResult->code, array(429, 500, 503)));
    
                if (!empty($sendResult->error) || $sendResult->code > 200) {
                    $this->app->log('error reset-password: [curl_error] ' . $sendResult->error . ' [response_content] ' .  $sendResult->content, 'error');
                    $this->userRepo->addMessage('error', "Email not sent.");
                    $this->session->unset('otp_token');
                    $success = false;
                } else {
                    $route = 'register';
                    $this->userRepo->addMessage('success', "Email sent to {$user['email']} successfully.");
                }
            } else {
                $this->app->log('error reminder-send: failed to get access token: ' . $tokenResponse->content, 'error');
                $this->userRepo->addMessage('error', "something went wrong with the server.");
                $this->session->unset('otp_token');
                $success = false;
            }
        }

        $this->session->set('flash', $this->userRepo->getMessages());

        $output->redirect($this->app->urlRoute($route));

        return array($input, $output, $success);
    }
}