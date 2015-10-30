<?php

class Rule
{
    private $container;

    public function __construct()
    {
        $this->container = Container::getInstance();
    }

    public function getSQL($sqlFile)
    {
        $file = $this->container->bundle . 'sql/' . $sqlFile . '.sql';

        if (!file_exists($file)) {
            CommandHelper::implementationNotice($sqlFile, '.sql file');
            exit(1);
        }

        return file_get_contents($file);
    }

    public function getCriteria($criteriaFile)
    {
        $file = $this->container->bundle . 'mail/' . $criteriaFile . '.txt';

        if (!file_exists($file)) {
            CommandHelper::implementationNotice($criteriaFile, '.txt file');
            exit(1);
        }

        $criteria = file_get_contents($file);
        return trim($criteria);
    }

    public function getHandler($handlerClass)
    {

        if (!class_exists($handlerClass)) {
            CommandHelper::implementationNotice($handlerClass, ' class');
            exit(1);
        } else {
            $handler = new $handlerClass;
        }

        if (!in_array('HandlerInterface', class_implements($handler))) {
            CommandHelper::implementationError($handlerClass, 'HandlerInterface');
            exit(1);
        }

        return $handler;
    }
}
