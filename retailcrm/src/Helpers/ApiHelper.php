<?php

class ApiHelper
{

    private $api;
    private $logger;
    private $container;

    public function __construct($api)
    {
        $this->api = $api;
        $this->logger = new Logger();
        $this->container = Container::getInstance();
    }

    public function uploadOrders($orders, $check = true)
    {
        $timemark = date('Y-m-d H:i:s');

        if ($check) {
            $orders = $this->prepareOrders($orders);
        }

        $splitOrders = array_chunk($orders, 50);

        foreach ($splitOrders as $orders) {
            $this->api->ordersUpload($orders);
            time_nanosleep(0, 250000000);
        }

        $this->logger->put($timemark, $this->container->ordersLog);
    }

    public function updateOrders($orders)
    {
        $timemark = date('Y-m-d H:i:s');
        foreach ($orders as $order) {
            $this->api->ordersEdit($order);
            echo "\033[0;36m" . $order['externalId'] . " updated\e[0m\n";
            time_nanosleep(0, 250000000);
        }

        $this->logger->put($timemark, $this->container->ordersUpdatesLog);
    }

    public function ordersHistory()
    {
        $response = $this->api->ordersHistory(
            new DateTime(
                DataHelper::getDate($this->container->ordersHistoryLog)
            )
        );

        if (!is_null($response)) {
            $this->logger->put(
                $response->getGeneratedAt(),
                $this->container->ordersHistoryLog
            );
            return $response['orders'];
        } else {
            return array();
        }
    }

    public function uploadCustomers($customers)
    {
        $timemark = date('Y-m-d H:i:s');
        $splitCustomers = array_chunk($customers, 50);

        foreach ($splitCustomers as $customers) {
            $this->api->customersUpload($customers);
            time_nanosleep(0, 250000000);
        }

        $this->logger->put($timemark, $this->container->customersLog);
    }

    public function updateCustomers($customers, $searchEdit = false)
    {
        $timemark = date('Y-m-d H:i:s');
        foreach ($customers as $customer) {
            if ($searchEdit) {
                $this->checkCustomers($customer, true);
            } else {
                $this->api->customersEdit($customer);
            }
            time_nanosleep(0, 250000000);
        }

        $this->logger->put($timemark, $this->container->customersUpdateLog);
    }

    // For future
    public function customersHistory()
    {
        $response = $this->api->customersHistory(
            new DateTime(
                DataHelper::getDate($this->container->customersHistoryLog)
            )
        );

        if (!is_null($response)) {
            $this->logger(
                $response->getGeneratedAt(),
                $this->container->customersHistoryLog
            );
            return $response['customers'];
        } else {
            return array();
        }
    }

    private function prepareOrders($orders)
    {
        foreach ($orders as $idx => $order) {

            $customer = array();

            $customer['externalId'] = $order['customerId'];

            if (isset($order['firstName'])) {
                $customer['firstName'] = $order['firstName'];
            }

            if (isset($order['lastName'])) {
                $customer['lastName'] = $order['lastName'];
            }

            if (isset($order['patronymic'])) {
                $customer['patronymic'] = $order['patronymic'];
            }

            if (!empty($order['delivery']['address'])) {
                $customer['address'] = $order['delivery']['address'];
            }

            if (isset($order['phone'])) {
                $customer['phones'][]['number'] = $order['phone'];
            }

            if (isset($order['email'])) {
                $customer['email'] = $order['email'];
            }

            $checkResult = $this->checkCustomers($customer);

            if ($checkResult === false) {
                unset($orders[$idx]["customerId"]);
            } else {
                $orders[$idx]["customerId"] = $checkResult;
            }
        }

        return $orders;
    }

    private function checkCustomers($customer, $searchEdit = false)
    {

        $criteria = array(
            'name' => (isset($customer['phones'][0]['number'])) ? $customer['phones'][0]['number'] : $customer['lastName'],
            'email' => (isset($customer['email'])) ? $customer['email'] : ''
        );

        $search = $this->api->customersList($criteria);

        if (!is_null($search)) {
            if(empty($search['customers'])) {
                if(!is_null($this->api->customersEdit($customer))) {
                    return $customer["externalId"];
                } else {
                    return false;
                }
            } else {
                $_externalId = null;

                foreach ($search['customers'] as $_customer) {
                    if (!empty($_customer['externalId'])) {
                        $_externalId = $_customer['externalId'];
                        break;
                    }
                }

                if (is_null($_externalId)) {
                    $customerFix = array(
                        'id' => $search['customers'][0]['id'],
                        'externalId' => $customer['externalId']
                    );
                    $response = $this->api->customersFixExternalIds(
                        array($customerFix)
                    );
                    $_externalId = $customer['externalId'];
                };

                if ($searchEdit) {
                    $customer['externalId'] = $_externalId;
                    $this->api->customersEdit($customer);
                }

                return $_externalId;
            }
        } else {
            return false;
        }
    }

    /**
     * Export deliveries
     *
     * @param array $deliveries
     */
    public function uploadDeliveryTypes($deliveryTypes)
    {
        foreach ($deliveryTypes as $type) {
            $this->api->deliveryTypesEdit($type);
            time_nanosleep(0, 250000000);
        }
    }

    /**
     * Export deliveries
     *
     * @param array $deliveries
     */
    public function uploadDeliveryServices($deliveryServices)
    {
        foreach ($deliveryServices as $service) {
            $this->api->deliveryServicesEdit($service);
            time_nanosleep(0, 250000000);
        }
    }

    /**
     * Export payments
     *
     * @param array $payments
     */
    public function uploadPaymentTypes($payments)
    {
        foreach ($payments as $payment) {
            $this->api->paymentTypesEdit($payment);
            time_nanosleep(0, 250000000);
        }
    }

    /**
     * Export payment statuses
     *
     * @param array $paymentStatuses
     */
    public function uploadPaymentStatuses($paymentStatuses)
    {
        foreach ($paymentStatuses as $status) {
            $this->api->paymentStatusesEdit($status);
            time_nanosleep(0, 250000000);
        }
    }

    /**
     * Export statuses
     *
     * @param unknown_type $statuses
     */
    public function uploadStatuses($statuses)
    {
        foreach ($statuses as $status) {
            $this->api->statusesEdit($status);
            time_nanosleep(0, 250000000);
        }
    }

}
