<?php

namespace EasyCloudRequest\Core\Support;

class Response
{
    /**
     * response status code
     * @var int
     */
    public $code = 200;

    /**
     * response message
     *
     * @var string
     */
    public $message= '';

    /**
     * response with data
     *
     * @var array
     */
    public $data = [];

    public function __construct(
        $code = 200,
        $data = []
    ) {
        $this->code = $code;
        $this->message = $code === 200 ? 'success' : 'error';
        $this->data = $data;
    }
}
