<?php

class Pipe_LockAcquire {
    public $app;

    public function args($args) {
        list(
            $this->app,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $lockFile = $this->app->dirRoot($input->getFrom($input->data, 'lock_file'));
        $lockTtl = $input->getFrom($input->data, 'lock_ttl', 30 * 5);

        if (!$this->acquireLock($lockFile, $lockTtl)) {
            $output->content = json_encode(['error' => 'Process already running']);
            $output->header['content-type'] = 'application/json';
            $output->code = 429;

            $success = false;
        }

        return array($input, $output, $success);
    }

    private function acquireLock($lockFile, $lockTtl) {
        if ($this->isLocked($lockFile, $lockTtl)) {
            return false;
        }
        file_put_contents($lockFile, "locked at " . date('c'));
        return true;
    }

    private function isLocked($lockFile, $lockTtl) {
        if (!file_exists($lockFile)) return false;
        $lockTime = filemtime($lockFile);
        if (time() - $lockTime > $lockTtl) {
            unlink($lockFile); 
            return false;
        }
        return true;
    }
}