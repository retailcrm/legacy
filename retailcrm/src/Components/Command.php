<?php

class Command
{

    private $run;
    private $uid;
    private $mail;
    private $ref;
    private $limit;
    private $update;
    private $container;
    private $debug;

    public function __construct($arguments)
    {
        $this->run  = (isset($arguments['e'])) ? trim($arguments['e']) : false;
        $this->uid  = (isset($arguments['p'])) ? trim($arguments['p']) : false;
        $this->ref  = (isset($arguments['r'])) ? trim($arguments['r']) : false;
        $this->mail = (isset($arguments['m'])) ? trim($arguments['m']) : false;
        $this->history = isset($arguments['h']) ? trim($arguments['h']) : false;

        $this->limit   = isset($arguments['l']);
        $this->update  = isset($arguments['u']);
        $this->custom  = isset($arguments['c']);
        $this->debug   = isset($arguments['d']);

        $this->container = Container::getInstance();

        $this->api = new RequestProxy(
            $this->container->settings['api']['url'],
            $this->container->settings['api']['key']
        );

        $this->requestHelper = new ApiHelper($this->api);
    }

    public function run()
    {
        if (!$this->run) {
            CommandHelper::runHelp();
            return;
        }

        $debug = new DebugHelper();
        if ($this->debug) {
            $debug->write(sprintf('Start %s', ucfirst($this->run)));
        }

        $command = sprintf('run%s', ucfirst($this->run));
        $this->$command();

        if ($this->debug) {
            $debug->write(sprintf('End %s', ucfirst($this->run)));
        }
    }

    public function runDump()
    {
        $dbUser = $this->container->settings['db']['user'];
        $dbPass = $this->container->settings['db']['password'];
        $dbName = $this->container->settings['db']['dbname'];
        $dbHost = $this->container->settings['db']['host'];

        switch ($this->container->settings['db']['driver']) {
            case 'mysql':
                $cmd = sprintf(
                    'mysqldump -u %s --password=%s --host=%s %s',
                    $dbUser, $dbPass, $dbHost, $dbName
                );
                break;
            case 'pgsql':
                $cmd = sprintf(
                    'PGPASSWORD=\'%s\' pg_dump -U %s -h %s %s',
                    $dbPass, $dbUser, $dbHost, $dbName
                );
                break;
            default:
                CommandHelper::dumpNotice();
                return;
        }

        passthru(
            sprintf(
                '%s | gzip --best > %sdbdump.sql.gz',
                $cmd, $this->container->saveDir
            )
        );
    }

    public function runIcml()
    {
        $categories = new CategoriesBuilder();
        $offers = new OffersBuilder();
        $icml = new IcmlHelper($this->container->shopName, $this->container->icml);

        $icml->generate($categories->buildCategories(), $offers->buildOffers());
    }

    public function runOrders()
    {
        $builder = new OrdersBuilder();
        $orders = array();

        if ($this->update) {
            if ($this->uid) {
                $orders = $builder->buildOrdersUpdateById($this->uid);
            } elseif ($this->custom) {
                $orders = $builder->buildOrdersCustomUpdate();
            } elseif ($this->limit) {
                $orders = $builder->buildOrdersUpdate();
            } else {
                CommandHelper::updateNotice();
            }

            if (!empty($orders)) {
                $this->requestHelper->updateOrders($orders);
            }
        } else {
            $this->check = true;

            if ($this->uid) {
                $orders = $builder->buildOrdersById($this->uid);
            } elseif ($this->limit) {
                $orders = $builder->buildOrdersLast();
            } else {
                $this->check = false;
                $orders = $builder->buildOrders();
            }

            if (!empty($orders)) {
                $this->requestHelper->uploadOrders($orders, $this->check);
            }
        }
    }

    public function runCustomers()
    {
        $builder = new CustomersBuilder();
        $customers = array();

        if ($this->update) {
            if ($this->uid) {
                $customers = $builder->buildCustomersUpdateById($this->uid);
            } elseif ($this->custom) {
                $customers = $builder->buildCustomersCustomUpdate();
            } elseif ($this->limit) {
                $customers = $builder->buildCustomersUpdate();
            } else {
                CommandHelper::updateNotice();
            }

            if (!empty($customers)) {
                $this->requestHelper->updateCustomers($customers);
            }
        } else {
            if ($this->uid) {
                $customers = $builder->buildCustomersById($this->uid);
            } elseif ($this->limit) {
                $customers = $builder->buildCustomersLast();
            } else {
                $customers = $builder->buildCustomers();
            }

            if (!empty($customers)) {
                $this->requestHelper->uploadCustomers($customers);
            }
        }
    }

    public function runHistory()
    {
        $builder = new HistoryBuilder();

        if (!empty($this->history)) {
            switch ($this->history) {
                case 'orders':
                    $orders  = $this->requestHelper->ordersHistory();
                    if(!empty($orders)) $builder->buildOrdersHistory($orders);
                    break;
                case 'customers':
                    /**
                     * Waiting for v4
                     * $customers = $this->requestHelper->ordersCustomers();
                     * $builder->buildCustomersHistory($customers);
                     */
                    echo "\e[0;36mNot implemented yet\e[0m\n";
                    break;
                default:
                    CommandHelper::refHelp('history');
                    break;
            }
        } else {
            $orders  = $this->requestHelper->ordersHistory();
            if(!empty($orders)) $builder->buildOrdersHistory($orders);
            /**
             * Waiting for v4
             * $customers = $this->requestHelper->ordersCustomers();
             * $builder->buildCustomersHistory($customers);
             */
        }
    }

    public function runReferences()
    {
        $builder = new ReferencesBuilder();
        if (!empty($this->ref)) {
            $reference = array();
            switch ($this->ref) {
                case 'delivery-types':
                    $reference = $builder->buildDeliveryTypes();
                    $this->requestHelper->uploadDeliveryTypes($reference);
                    break;
                case 'delivery-services':
                    $reference = $builder->buildDeliveryServices();
                    $this->requestHelper->uploadDeliveryServices($reference);
                    break;
                case 'payment-types':
                    $reference = $builder->buildPaymentTypes();
                    $this->requestHelper->uploadPaymentTypes($reference);
                    break;
                case 'payment-statuses':
                    $reference = $builder->buildPaymentStatuses();
                    $this->requestHelper->uploadDeliveryStatuses($reference);
                    break;
                case 'statuses':
                    $reference = $builder->buildStatuses();
                    $this->requestHelper->uploadStatuses($reference);
                    break;
                default:
                    CommandHelper::refHelp('references');
                    break;
            }
        } else {
            $deliveryTypes = $builder->buildDeliveryTypes();
            $this->requestHelper->uploadDeliveryTypes($deliveryTypes);

            $deliveryServices = $builder->buildDeliveryServices();
            $this->requestHelper->uploadDeliveryServices($deliveryServices);

            $paymentTypes = $builder->buildPaymentTypes();
            $this->requestHelper->uploadPaymentTypes($paymentTypes);

            $paymentStatuses = $builder->buildPaymentStatuses();
            $this->requestHelper->uploadDeliveryStatuses($paymentStatuses);

            $statuses = $builder->buildStatuses();
            $this->requestHelper->uploadStatuses($statuses);
        }
    }

    public function runMail()
    {
        if (empty($this->mail)) {
            CommandHelper::paramNotice('-m');
            exit(1);
        }

        if (filter_var($this->mail, FILTER_VALIDATE_EMAIL)) {
            $mailer = new Mail($this->mail);
            $data   = $mailer->parse();

            if (!empty($data)) {
                $this->requestHelper->uploadOrders($data, true);
            }
        }
    }

    public function runAmo()
    {
        if (!isset($this->container->amocrm)) {
            CommandHelper::activateNotice('amocrm');
            exit(1);
        }

        $amo = new AmoRestApi(
            $this->container->amocrm['domain'],
            $this->container->amocrm['login'],
            $this->container->amocrm['key']
        );

        $rule = new Rule();

        $handler = $rule->getHandler('AmoHandler');
        $data = $handler->prepare($amo);

        if (!empty($data) && !empty($data['customers'])) {
            $this->requestHelper->uploadCustomers($data['customers']);
            if (!empty($data['orders'])) {
                $this->requestHelper->uploadOrders($data['orders'], true);
            }
        }
    }
}
