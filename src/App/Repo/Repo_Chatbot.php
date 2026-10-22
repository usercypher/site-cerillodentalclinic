<?php

class Repo_Chatbot extends Lib_DatabaseHelper {
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
        parent::setTable('chatbot');
    }

    public function validateAndInsert($data) {
        $this->insert($data['chatbot']);

        $this->addMessage('success', 'Chatbot created successfully.');

        return true;
    }

    public function validateAndUpdate($data) {
        $this->update($data['chatbot']);

        $this->addMessage('success', 'Chatbot updated successfully.');

        return true;
    }

    public function validateAndDeleteBatch($data) {
        if (empty($data['chatbot']['id'])) {
            $this->addMessage('error', 'Delete failed. Select at least one.');
            return false;
        }

        $this->deleteBatch($data['chatbot']['id']);

        $this->addMessage('success', 'Chatbot deleted successfully.');

        return true;
    }
}
?>