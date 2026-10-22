<?php

class Repo_User extends Lib_DatabaseHelper {
    public function args($args) {
        list(
            $app,
            $database
        ) = $args;

        $database->connect([
            'host' => $app->getEnv('DB_HOST'), 
            'port' => $app->getEnv('DB_PORT'),
            'name' => $app->getEnv('DB_NAME'),
            'user' => $app->getEnv('DB_USER'),
            'pass' => $app->getEnv('DB_PASS'),
            'time' => $app->getEnv('DB_TIME', '+00:00')
        ]);

        parent::setDb($database);
        parent::setTable('user');
    }

    public function validateAndInsert($data) {
        if ($this->exists('WHERE email = ?', array($data['user']['email']))) {
            $this->addMessage('error', 'Email Already Exists.');
            return false;
        }
        $data['user']['password'] = password_hash($data['user']['password'], PASSWORD_BCRYPT);

        $this->insert($data['user']);

        $this->addMessage('success', 'User created successfully.');

        return true;
    }

    public function validateAndUpdate($data) {
        $userData = $data['user'];
        
        if (isset($userData['email']) && $userData['email'] != $data['current_email'] && $this->exists('WHERE email = ?', array($userData['email']))) {
            $this->addMessage('error', 'Email Already Exists.');
            return false;
        }

        if (isset($userData['password'])) {
            $user = $this->one('WHERE id = ?', array($userData['id']));

            if ($userData['password'] !== $data['confirm_password']) {
                $this->addMessage('error', 'Password not match.');
                return false;
            }

            if (empty($data['is_reset_password']) && !password_verify($data['current_password'], $user['password'])) {
                $this->addMessage('error', 'Incorrect password.');
                return false;
            }

            $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);
        }

        $this->update($userData);

        $this->addMessage('success', 'User updated successfully.');

        return true;
    }

    public function validateAndDelete($data) {
        $userData = $data['user'];

        $user = $this->one('WHERE id = ?', array($userData['id']));

        if (!password_verify($userData['password'], $user['password'])) {
            $this->addMessage('error', 'Incorrect Password.');
            return false;
        }

        $this->delete($userData['id']);

        $this->addMessage('success', 'User deleted successfully.');

        return true;
    }

    public function validateAndDeleteBatch($data) {
        if (empty($data['user']['id'])) {
            $this->addMessage('error', 'Delete failed. Select at least one.');
            return false;
        }

        $this->deleteBatch($data['user']['id']);

        $this->addMessage('success', 'User deleted successfully.');

        return true;
    }
    

    public function login($data) {
        $user = $this->one('WHERE email = ?', array($data['user']['email']));

        if (!$user || !password_verify($data['user']['password'], $user['password'])) {
            $this->addMessage('error', 'Incorrect email or password.');
            return false;
        }

        return $user;
    }
}
?>