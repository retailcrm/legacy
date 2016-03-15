<?php

class RequestProxy
{
    private $api;
    private $logger;
    private $container;

    public function __construct($url, $key)
    {
        $this->api =  new ApiClient($url,$key);
        $this->logger = new Logger();
        $this->container = Container::getInstance();
    }

    public function __call($method, $arguments)
    {
        try {
            $response = call_user_func_array(array($this->api, $method), $arguments);

            if ($response->isSuccessful()) {
                return $response;
            } else {
                $this->logger->write(
                    "[$method] " . $response->getErrorMsg() . "\n",
                    $this->container->errorLog
                );

                if (isset($response['errors'])) {
                    foreach ($response['errors'] as $error) {
                        if (is_array($error)) {
                            $error = @implode(', ', $error);
                        }

                        $this->logger->write(
                            "[$method] $error \n",
                            $this->container->errorLog
                        );
                    }
                }

                return null;
            }
        } catch (CurlException $e) {
            $this->logger->write(
                "[$method] " . $e->getMessage() . "\n",
                $this->container->errorLog
            );

            return null;
        } catch (InvalidJsonException $e) {
            $this->logger->write(
                "[$method] " . $e->getMessage() . "\n",
                $this->container->errorLog
            );

            return null;
        } catch (InvalidArgumentException $e) {
            $this->logger->write(
                "[$method] " . $e->getMessage() . "\n",
                $this->container->errorLog
            );

            return null;
        }
    }
}
