<?php

class Pipe_LockRelease {
    public $app;

    public function args($args) {
        list(
            $this->app,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;

        $lockFile = $this->app->dirRoot($input->getFrom($input->data, 'lock_file'));

        if (file_exists($lockFile)) {
            unlink($lockFile);
        }

        return array($input, $output, $success);
    }
}