<?php

class Pipe_User_ForgotPassword {
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

        $user = $this->userRepo->one('WHERE email = ?', array($data['user']['email']));

        if (!$user) {
            $this->userRepo->addMessage('error', 'Account not found.');
            $this->session->unset('otp_token');
            $success = false;
        } else {
            $this->session->set('forgot_password_user', $user);

            $tokenResponse = $this->gmail->getToken(
                $this->app->getEnv('OAUTH2_CLIENT_ID'),
                $this->app->getEnv('OAUTH2_CLIENT_SECRET'),
                $this->app->getEnv('OAUTH2_REFRESH_TOKEN')
            );
            $tokenData = json_decode($tokenResponse->content);
            if (!empty($tokenData->access_token)) {
                $subject = "Subject: Password Reset Code";
                $body = "
Dear {$user['firstname']} {$user['middlename']} {$user['lastname']},

We received a request to reset the password for your account. To proceed with resetting your password, please use the following code:

{$this->session->get('otp_token')}

If you did not request a password reset, please ignore this email.

To reset your password, simply enter the code above in the password reset page on our website.

If you have any issues or need further assistance, feel free to contact our support team at dentalclinic010100@gmail.com.

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
                    $route = 'reset-password';
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