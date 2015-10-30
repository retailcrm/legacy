<?php

class CustomersBuilder extends Builder
{

    /**
     * Get all customers
     *
     * @return array
     */
    public function buildCustomers()
    {
        $query = $this->rule->getSQL('customers');
        $handler = $this->rule->getHandler('CustomersHandler');
        $this->sql = $this->container->db->prepare($query);

        return $this->build($handler);
    }

    /**
     * Get all new customers since last run
     *
     * @return array
     */
    public function buildCustomersLast()
    {
        $lastSync = DataHelper::getDate($this->container->customersLog);

        $query = $this->rule->getSQL('customers_last');
        $handler = $this->rule->getHandler('CustomersHandler');
        $this->sql = $this->container->db->prepare($query);
        $this->sql->bindParam(':lastSync', $lastSync);

        return $this->build($handler);
    }

    /**
     * Get new customers by id
     *
     * @return array
     */
    public function buildCustomersById($uidString)
    {
        $query = $this->rule->getSQL('customers_uid');
        $handler = $this->rule->getHandler('CustomersHandler');
        $this->sql = $this->container->db->prepare($query);
        $this->sql->bindParam(':orderIds', $uids);
        $uids = DataHelper::explodeUids($uidString);

        return $this->build($handler);
    }

    /**
     * Get all updated customers since last run
     *
     * @return array
     */
    public function buildCustomersUpdate()
    {
        $lastSync = DataHelper::getDate($this->container->customersUpdatesLog);

        $query = $this->rule->getSQL('customers_update_last');
        $handler = $this->rule->getHandler('CustomersHandler');
        $this->sql = $this->container->db->prepare($query);
        $this->sql->bindParam(':lastSync', $lastSync);

        return $this->build($handler);
    }

    /**
     * Get updated customer by id
     *
     * @return array
     */
    public function buildCustomersUpdateById($uidString)
    {
        $query = $this->rule->getSQL('customers_update_uid');
        $handler = $this->rule->getHandler('CustomersHandler');
        $this->sql = $this->container->db->prepare($query);
        $this->sql->bindParam(':orderIds', $uids);
        $uids = DataHelper::explodeUids($uidString);

        return $this->build($handler);
    }

    /**
     * Custom update
     *
     * @return array
     */
    public function buildCustomersCustomUpdate()
    {
        $query = $this->rule->getSQL('customers_update_custom');
        $handler = $this->rule->getHandler('CustomersCustomUpdateHandler');
        $this->sql = $this->container->db->prepare($query);

        return $this->build($handler);
    }
}
