<?php

abstract class Builder
{
    protected $sql;
    protected $rule;
    protected $container;
    protected $logger;

    public function __construct()
    {
        $this->rule = new Rule();
        $this->logger = new Logger();
        $this->container = Container::getInstance();
    }

    protected function build($handler)
    {
        try {
            $this->sql->execute();
            $result = $this->sql->fetchAll(PDO::FETCH_ASSOC);
            return $handler->prepare($result);
        } catch (PDOException $e) {
            $this->logger->write(
                'PDO: ' . $e->getMessage(),
                $this->container->errorLog
            );
            return false;
        }
    }

    protected function update($handler, $data)
    {
        return $handler->prepare($data);
    }
}
