<?php

class OrdersBuilder extends Builder
{
    /**
     * Get all orders
     *
     * @return array
     */
    public function buildOrders()
    {
        $query = $this->rule->getSQL('orders');
        $handler = $this->rule->getHandler('OrdersHandler');
        $this->sql = $this->container->db->prepare($query);

        return $this->build($handler);
    }

    /**
     * Get all new orders since last run
     *
     * @return array
     */
    public function buildOrdersLast()
    {
        $lastSync = DataHelper::getDate($this->container->ordersLog);

        $query = $this->rule->getSQL('orders_last');
        $handler = $this->rule->getHandler('OrdersHandler');
        $this->sql = $this->container->db->prepare($query);
        $this->sql->bindParam(':lastSync', $lastSync);

        return $this->build($handler);
    }

    /**
     * Get new orders by id
     *
     * @return array
     */
    public function buildOrdersById($uidString)
    {
        $query = $this->rule->getSQL('orders_uid');
        $handler = $this->rule->getHandler('OrdersHandler');
        $this->sql = $this->container->db->prepare($query);
        $uids = DataHelper::explodeUids($uidString);
        $this->sql->bindParam(':orderIds', $uids);

        return $this->build($handler);
    }

    /**
     * Get all updated orders since last run
     *
     * @return array
     */
    public function buildOrdersUpdate()
    {
        $lastSync = DataHelper::getDate($this->container->ordersUpdatesLog);

        $query = $this->rule->getSQL('orders_update_last');
        $handler = $this->rule->getHandler('OrdersUpdateHandler');
        $this->sql = $this->container->db->prepare($query);
        $this->sql->bindParam(':lastSync', $lastSync);

        return $this->build($handler);
    }

    /**
     * Get updated orders by id
     *
     * @return array
     */
    public function buildOrdersUpdateById($uidString)
    {
        $uids = DataHelper::explodeUids($uidString);
        $query = $this->rule->getSQL('orders_update_uid');
        $handler = $this->rule->getHandler('OrdersUpdateHandler');
        $this->sql = $this->container->db->prepare($query);
        $this->sql->bindParam(':orderIds', $uids);

        return $this->build($handler);
    }

    /**
     * Custom update
     *
     * @return array
     */
    public function buildOrdersCustomUpdate()
    {
        $query = $this->rule->getSQL('orders_update_custom');
        $handler = $this->rule->getHandler('OrdersCustomUpdateHandler');
        $this->sql = $this->container->db->prepare($query);

        return $this->build($handler);
    }
}
