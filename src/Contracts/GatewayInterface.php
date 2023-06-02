<?php

namespace EasyCloudRequest\Core\Contracts;

use EasyCloudRequest\Core\Support\RequestBag;
use EasyCloudRequest\Core\Support\Response;

interface GatewayInterface
{
    /**
     *
     * @param \EasyCloudRequest\Core\Support\RequestBag $requestBag
     * @return \EasyCloudRequest\Core\Support\Response
     */
    public function requests(RequestBag $requestBag): Response;
}
