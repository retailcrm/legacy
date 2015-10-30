<?php

class OffersBuilder extends Builder
{
    /**
     * getOffers
     *
     * @return array
     */
    public function buildOffers()
    {
        $query = $this->rule->getSQL('offers');
        $handler = $this->rule->getHandler('OffersHandler');
        $this->sql = $this->container->db->prepare($query);
        $this->sql->bindParam(':shop_url', $this->container->shopUrl);

        return $this->build($handler);
    }
}
