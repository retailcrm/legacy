<?php

class Logger
{
    private $rotate;
    private $push;
    private $files;

    public function __construct($rotate = true, $push = true, $files = 5)
    {
        $this->rotate = $rotate;
        $this->push = $push;
        $this->files = $files;

        $this->container = Container::getInstance();
    }

    public function write($data, $file)
    {
        // message prefix with current time
        $timestamp = date('Y-m-d H:i:s');
        $data = "[$timestamp]" . $data;

        if(!file_exists($file)) {
            touch($file);
        }

        // if filesize more than 5 Mb rotate it
        if ($this->rotate && filesize($file) > 5242880) {
            $this->rotate($file);
        }

        error_log($data, 3, $file);

        if ($this->push) {
            $this->push($data);
        }
    }

    public function put($data, $file)
    {
        file_put_contents($file, $data);
    }

    private function rotate($file)
    {
        $path = pathinfo($file);
        $rotate = implode('', array(
            $path['dirname'],
            '/',
            $path['filename'],
            date('YmdHis'),
            '.',
            $path['extension']
        ));

        copy($file, $rotate);
        $this->clean($file);

        $files = glob($path['dirname'] . '/' . "*.log");

        if (0 === $this->files) {
            return;
        }

        if (count($files) > $this->files) {
            natsort($files);
            foreach (array_slice($files, $this->files) as $log) {
                if (is_writable($log)) {
                    unlink($log);
                }
            }
        }
    }

    private function clean($file)
    {
        file_put_contents($file, '');
    }

    /**
     * Push log message to external source (default: mail)
     *
     * @param string $message
     * @param string $types
     *
     * @todo add mq, db, socket, http
     */
    private function push($message, $type = 'mail')
    {
        $methodName = 'push' . ucfirst($type);

        if (!method_exists($this, $methodName)) {
            throw new InvalidArgumentException("Method \"$methodName\" not found");
        } else {
            $this->$methodName($message);
        }
    }

    private function pushMail($message)
    {
        $domain    = $this->container->domain;
        $recipient = $this->container->support;
        $subject   = 'Legacy notification';
        $headers   = 'From: noreply@retailcrm.ru' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        $message = "New log message from $domain:\n\n$message";

        mail($recipient, $subject, $message, $headers);
    }
}
