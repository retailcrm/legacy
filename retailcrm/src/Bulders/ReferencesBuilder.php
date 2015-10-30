<?php

class ReferencesBuilder extends Builder
{
    public function buildDeliveryTypes()
    {
        $query = $this->rule->getSQL('delivery_types');
        $handler = $this->rule->getHandler('DeliveryTypesHandler');
        $this->sql = $this->container->db->prepare($query);

        return $this->build($handler);
    }

    public function buildDeliveryServices()
    {
        $query = $this->rule->getSQL('delivery_services');
        $handler = $this->rule->getHandler('DeliveryServicesHandler');
        $this->sql = $this->container->db->prepare($query);

        return $this->build($handler);
    }

    public function buildPaymentTypes()
    {
        $query = $this->rule->getSQL('payment_types');
        $handler = $this->rule->getHandler('PaymentTypesHandler');
        $this->sql = $this->container->db->prepare($query);

        return $this->build($handler);
    }

    public function buildPaymentStatuses()
    {
        $query = $this->rule->getSQL('payment_statuses');
        $handler = $this->rule->getHandler('PaymentStatusesHandler');
        $this->sql = $this->container->db->prepare($query);

        return $this->build($handler);
    }

    public function buildStatuses()
    {
        $query = $this->rule->getSQL('statuses');
        $handler = $this->rule->getHandler('StatusesHandler');
        $this->sql = $this->container->db->prepare($query);

        return $this->build($handler);
    }

}
