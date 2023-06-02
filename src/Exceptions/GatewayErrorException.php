<?php

namespace EasyCloudRequest\Exceptions;

use \EasyCloudRequest\Core\Exceptions\Exception;

class GatewayErrorException extends Exception
{
    /**
     * @var array
     */
    public $raw = [];
    /**
     * GatewayErrorException constructor.
     *
     * @param array $raw
     */
    public function __construct($message, $code, array $raw = [])
    {
        parent::__construct($message, intval($code));
        $this->raw = $raw;
    }
}
