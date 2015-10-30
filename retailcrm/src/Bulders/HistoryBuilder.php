<?php

class HistoryBuilder extends Builder
{
    /**
     * Update orders
     *
     * @return array
     */
    public function buildOrdersHistory($orders)
    {
        $handler = $this->rule->getHandler('OrdersHistoryHandler');
        return $this->update($handler, $orders);
    }

    /**
     * updateCustomers
     *
     * @return array
     */
    public function buildCustomersHistory($customers)
    {
        $handler = $this->rule->getHandler('CustomersHistoryHandler');
        return $this->update($handler, $customers);
    }
}
