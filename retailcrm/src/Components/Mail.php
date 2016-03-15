<?php

class Mail
{

    protected $mailBox;
    protected $mailSettings;

    public function __construct($mailBox) {
        $this->container = Container::getInstance();
        $this->rule = new Rule();

        $this->mailBox = $mailBox;

        if (is_array($this->container->mail)) {
            if (isset($this->container->mail[$mailBox])) {
                $this->mailSettings = explode(
                    ',',
                    $this->container->mail[$mailBox]
                );
            } else {
                CommandHelper::settingsFailure($mailBox);
                exit(1);
            }
        } else {
            CommandHelper::activateNotice('mail');
            exit(1);
        }

    }


    public function parse()
    {

        $server = new Server(
            $this->mailSettings[2],
            $this->mailSettings[3],
            $this->mailSettings[4]
        );

        $server->setAuthentication(
            $this->mailSettings[0],
            $this->mailSettings[1]
        );

        if (!empty($this->mailSettings[5])) {
            $server->setMailBox($this->mailSettings[5]);
        }

        $mailCriteria = $this->clean($this->mailBox, 'criteria');
        $mailHandler  = $this->clean($this->mailBox, 'handler');

        $criteria = $this->rule->getCriteria($mailCriteria);
        $handler = $this->rule->getHandler($mailHandler);
        error_reporting(E_ERROR | E_PARSE);
        $messages = $server->search($criteria);

        return $handler->prepare($messages);
    }

    private function clean($mail, $type) {
        if ($type == 'criteria') {
            $string = preg_replace('/[\@\.\,\-]/', '_', $mail);
            return strtolower($string);
        }

        if ($type == 'handler') {
            $string = preg_replace('/[\@\.\,\-]/', '_', $mail);
            $string = explode('_', $string);
            $string = array_map("ucfirst", $string);
            $string = implode('', $string);

            if (is_int(substr($string, 0, 1))) {
                $string = 'Numbered' . $string;
            }

            return $string . 'Handler';
        }
    }
}
