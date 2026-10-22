<?php

class Repo_Service extends Lib_DatabaseHelper {
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
        parent::setTable('service');
    }

    public function validateAndInsert($data) {
        if ($this->exists('WHERE name = ?', array($data['service']['name']))) {
            $this->addMessage('error', 'Name already exist.');
            return false;
        }

        $this->insert($data['service']);

        $this->addMessage('success', 'Service created successfully.');

        return true;
    }

    public function validateAndUpdate($data) {
        if ($data['service']['name'] != $data['current']['service']['name'] && $this->exists('WHERE name = ?', array($data['service']['name']))) {
            $this->addMessage('error', 'Name already exist.');
            return false;
        }

        $this->update($data['service']);

        $this->addMessage('success', 'Service updated successfully.');

        return true;
    }

    public function validateAndDeleteBatch($data) {
        if (empty($data['service']['id'])) {
            $this->addMessage('error', 'Delete failed. Select at least one.');
            return false;
        }

        $this->deleteBatch($data['service']['id']);

        $this->addMessage('success', 'Service deleted successfully.');

        return true;
    }
}
?>