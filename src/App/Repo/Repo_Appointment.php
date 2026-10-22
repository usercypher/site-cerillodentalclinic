<?php

class Repo_Appointment extends Lib_DatabaseHelper {
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
        parent::setTable('appointment');
    }

    public function validateAndInsert($data) {
        $this->insert($data['appointment']);
        if ($data['appointment']['time_start'] === $data['appointment']['time_end']) {
            $this->addMessage('error', 'Start time and end time cannot be the same.');
            return false;
        }

        $this->addMessage('success', 'Appointment created successfully.');

        return true;
    }

    public function validateAndUpdate($data) {
        $this->update($data['appointment']);

        $this->addMessage('success', 'Appointment updated successfully.');

        return true;
    }

    public function validateAndUpdateBatch($data) {
        if (empty($data['appointment'])) {
            $this->addMessage('error', 'update failed. Select at least one.');
            return false;
        }

        $this->updateBatch($data['appointment']);

        $this->addMessage('success', 'Appointment updated successfully.');

        return true;
    }

    public function validateAndDeleteBatch($data) {
        if (!isset($data['appointment']['id'])) {
            $this->addMessage('error', 'Delete failed. Select at least one.');
            return false;
        }

        $this->deleteBatch($data['appointment']['id']);

        $this->addMessage('success', 'Appointment deleted successfully.');

        return true;
    }

}
?>