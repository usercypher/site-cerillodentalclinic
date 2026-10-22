<?php

class Repo_Message extends Lib_DatabaseHelper {
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
        parent::setTable('message');
    }

    public function validateAndInsert($data) {
        $this->insert($data['message']);

        $this->addMessage('success', 'message created successfully.');

        return true;
    }

    public function validateAndUpdate($data) {
        $this->update($data['message']);

        $this->addMessage('success', 'message updated successfully.');

        return true;
    }

    public function validateAndDeleteBatch($data) {
        if (empty($data['message']['id'])) {
            $this->addMessage('error', 'Delete failed. Select at least one.');
            return false;
        }

        $this->deleteBatch($data['message']['id']);

        $this->addMessage('success', 'message deleted successfully.');

        return true;
    }
}
?>