<?php

class CategoriesBuilder extends Builder
{

    /**
     * getCategories
     *
     * @return array
     */
    public function buildCategories()
    {
        $query = $this->rule->getSQL('categories');
        $handler = $this->rule->getHandler('CategoriesHandler');
        $this->sql = $this->container->db->prepare($query);

        return $this->build($handler);

    }
}
