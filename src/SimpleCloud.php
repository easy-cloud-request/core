<?php

namespace EasyCloudRequest\Core;

use EasyCloudRequest\Core\Contracts\GatewayInterface;
use EasyCloudRequest\Core\Exceptions\InvalidArgumentException;
use EasyCloudRequest\Core\Support\Config;
use EasyCloudRequest\Core\Support\RequestBag;

class SimpleCloud
{
    /**
     * @var Config
     */
    protected $config;

    protected $defaultGateway;

    protected $customCreators;

    /**
     * Constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config instanceof Config ? $config : new Config($config);
        if (!empty($config['default'])) {
            $this->setDefaultGateway($config['default']);
        }
    }

    public function requests(RequestBag $bag)
    {
        $gateway = $this->gateway()->requests($bag);
        $bag->reset();

        return $gateway;
    }

    /**
     * get the sms send handle
     *
     * @param string $name
     * @return GatewayInterface
     */
    public function gateway($name = null)
    {
        $name = $name ?: $this->getDefaultGateway();
        return $this->createGateway($name);
    }

    /**
     * get the default sms send handle
     *
     * @return mixed
     */
    public function getDefaultGateway()
    {
        if (empty($this->defaultGateway)) {
            throw new \RuntimeException('No default gateway configured.');
        }
        return $this->defaultGateway;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function formatGatewayClassName($name)
    {
        if (class_exists($name)) {
            return $name;
        }
        $name = ucfirst(str_replace(['-', '_', ''], '', $name));
        return __NAMESPACE__ . "\\Gateways\\{$name}\\Gateway";
    }

    /**
     * @param string $name
     * @return mixed
     * @throws InvalidArgumentException | GatewayInterface
     */
    protected function createGateway($name)
    {
        if (isset($this->customCreators[$name])) {
            $gateway = $this->callCustomCreator($name);
        } else {
            $className = $this->formatGatewayClassName($name);
            if (class_exists($name)) {
                $name = lcfirst(str_replace(['\/', '\\', 'EasyCloudRequest', 'Gateway'], '', $name));
            }
            $gateway = $this->makeGateway($className, $this->config->get("gateway.{$name}"));

            if (!($gateway instanceof GatewayInterface)) {
                throw new InvalidArgumentException(sprintf('Gateway "%s" not inherited from %s.', $name, GatewayInterface::class));
            }
        }

        return $gateway->setHttpConfig($this->config->get('http_config', []));
    }

    /**
     * create handle object
     *
     * @param $gateway
     * @param $config
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function makeGateway($gateway, $config)
    {
        if (!class_exists($gateway)) {
            throw new InvalidArgumentException(sprintf('Gateway "%s" not exists.', $gateway));
        }
        return new $gateway($config);
    }

    /**
     * Set default gateway name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setDefaultGateway($name)
    {
        $this->defaultGateway = $name;
        return $this;
    }

    /**
     * Call a custom gateway creator.
     *
     * @param string $gateway
     *
     * @return mixed
     */
    protected function callCustomCreator($gateway)
    {
        return \call_user_func($this->customCreators[$gateway], $this->config->get("gateways.{$gateway}", []));
    }

    /**
     * Register a custom driver creator Closure.
     *
     * @param string   $name
     * @param \Closure $callback
     *
     * @return $this
     */
    public function extend($name, \Closure $callback)
    {
        $this->customCreators[$name] = $callback;

        return $this;
    }
}
