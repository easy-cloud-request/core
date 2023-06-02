<?php

namespace EasyCloudRequest\Core\Gateways;

use EasyCloudRequest\Core\Contracts\GatewayInterface;
use EasyCloudRequest\Core\Support\Config;
use EasyCloudRequest\Core\Support\RequestBag;
use EasyCloudRequest\Core\Traits\HasHttpRequest;

abstract class BaseGateway implements GatewayInterface
{
    use HasHttpRequest;

    /**
     * @var Config
     */
    protected $config;

    /**
     * for setting http config
     * @var array
     */
    protected $httpConfig;

    /**
     * request bag
     */
    protected $requestBag;

    protected $timeout = 5.0;

    /**
     * init config detail
     *
     * Gateway constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * @return mixed
     */
    public function getTimeout()
    {
        return $this->timeout ?: $this->config->get('timeout', $this->timeout);
    }

    /**
     * @param $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = floatval($timeout);
        return $this;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Config $config
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * set request bag
     * @param RequestBag $request
     * @return $this
     */
    public function setRequestBag(RequestBag $r)
    {
        $this->requestBag = $r;
        return $this;
    }

    public function setHttpConfig(array $c)
    {
        $this->httpConfig = $c;
        return $this;
    }
}
