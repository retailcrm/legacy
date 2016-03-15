<?php

class Container
{
    private static $_instanse = null;

    private $settings;
    private $support;
    private $db;
    private $logdir;
    private $savedir;
    private $ruledir;
    private $date;
    private $file;
    private $api;
    private $icml;
    private $mail;
    private $ordersLog;
    private $historyLog;
    private $errorLog;
    private $logformat;

    private function __construct()
    {
        $this->file = pathinfo(__FILE__);

        if (
            !file_exists(
                $this->file['dirname'] . '/../../data/config/settings.ini'
            )
        ) {
            CommandHelper::settingsNotice();
            exit(1);
        }

        $this->settings = parse_ini_file(
            $this->file['dirname'] . '/../../data/config/settings.ini', true
        );

        $this->support = $this->settings['general']['support'];

        if ($this->settings['db']['enabled']) {
            $driver = $this->settings['db']['driver'];

            if ($driver == 'mysql') {
                $charset = ';charset=utf8';
            } else {
                $charset = '';
            }

            try {
                $this->db = new PDO(
                    $driver .
                    ':host=' .
                    $this->settings['db']['host'] .
                    ';dbname='.$this->settings['db']['dbname'] . $charset,
                    $this->settings['db']['user'],
                    $this->settings['db']['password']
                );

                $this->db->exec("set names utf8");
                $this->db->exec("set global group_concat_max_len = 1000000");
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            }  catch (PDOException $e) {
                CommandHelper::activateNotice('database');
                exit(1);
            }

        }

        if ($this->settings['mail']['enabled']) {
            $this->mail = $this->settings['mail'];
        }

        if ($this->settings['amocrm']['enabled']) {
            $this->amocrm = $this->settings['amocrm'];
        }

        // Paths
        $this->logDir = $this->file['dirname'] . '/../../data/logs/';
        $this->saveDir = $this->file['dirname'] . '/../../data/upload/';
        $this->icml = $this->saveDir . $this->settings['general']['icml_file'];
        $this->bundle = $this->file['dirname'] . '/../../bundle/';

        // ICML
        $this->shopName = $this->settings['general']['shop_name'];
        $this->shopUrl  = $this->settings['general']['shop_url'];
        $this->domain   = $this->settings['general']['domain'];
        $this->date     = date('Y-m-d H:i:s');

        // Logs
        $this->logformat   = "[$this->date][$this->domain] ";
        $this->errorLog    = $this->logDir . 'error/error.log';
        $this->mailLog     = $this->logDir . 'mail/mail.log';

        $this->ordersLog            = $this->logDir . 'order/order.log';
        $this->ordersUpdatesLog     = $this->logDir . 'order/update.log';
        $this->ordersHistoryLog     = $this->logDir . 'order/history.log';

        $this->customersLog         = $this->logDir . 'customer/customer.log';
        $this->customersUpdatesLog  = $this->logDir . 'customer/update.log';
        $this->customersHistoryLog  = $this->logDir . 'customer/history.log';
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            throw new InvalidArgumentException("Property \"$name\" not found");
        }

        return $this->$name;
    }

    public static function getInstance() {
        if (null === self::$_instanse) {
            self::$_instanse = new self();
        }

        return self::$_instanse;
    }
}
