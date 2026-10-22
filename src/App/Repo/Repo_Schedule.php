<?php

class Repo_Schedule extends Lib_DatabaseHelper {
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
        parent::setTable('schedule');
    }

    public function validateAndInsert($data) {
        if ($this->exists('WHERE date = ? AND user_id = ?', array($data['schedule']['date'], $data['schedule']['user_id']))) {
            $this->addMessage('error', 'Date already approved.');
            return false;
        }
        if ($data['schedule']['time_start'] === $data['schedule']['time_end']) {
            $this->addMessage('error', 'Start time and end time cannot be the same.');
            return false;
        }
        $this->insert($data['schedule']);

        $this->addMessage('success', 'Schedule created successfully.');

        return true;
    }

    public function validateAndUpdateBatch($data) {
        if (empty($data['schedule'])) {
            $this->addMessage('error', 'Update failed. Select at least one.');
            return false;
        }
        
        $this->updateBatch($data['schedule']);

        $this->addMessage('success', 'Schedule updated successfully.');

        return true;
    }
    
    public function validateAndDeleteBatch($data) {
        if (empty($data['schedule']['id'])) {
            $this->addMessage('error', 'Delete failed. Select at least one.');
            return false;
        }

        $this->deleteBatch($data['schedule']['id']);

        $this->addMessage('success', 'Schedules deleted successfully.');

        return true;
    }
}
?>