<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache LICENSE, Version 2.0 (the
 * "LICENSE"); you may not use this file except in compliance
 * with the LICENSE.  You may obtain a copy of the LICENSE at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the LICENSE is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the LICENSE for the
 * specific language governing permissions and limitations
 * under the LICENSE.
 */

namespace EasyCloudRequest\Core\Support;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

class RequestBag
{
    public $method = '';
    public $url = '';

    public $queryParams = [];
    public $headerParams = [];
    public $body = '';
    public $stream = '';

    public $host = '';
    public $scheme = '';
    public $resourcePath = '';

    public function __construct(
        $method = null,
        $url = null,
        $queryParams = [],
        $headerParams = [],
        $body = null,
        $stream = null
    ) {
        $this->method = $method;
        $this->url = $url;

        if (!($url instanceof UriInterface)) {
            $uri = new Uri($url);
            $this->host = $uri->getHost();
            $this->scheme = $uri->getScheme();
            $this->resourcePath = $uri->getPath();
        }

        $this->queryParams = $queryParams;
        $this->headerParams = $headerParams;
        $this->body = $body;
        $this->stream = $stream;
    }


    public function reset()
    {
        $this->removeHeader();
        $this->removeQuery();
        $this->removeBody();
        $this->removeStream();
    }

    protected function removeHeader()
    {
        unset($this->headerParams);
    }

    protected function removeQuery()
    {
        unset($this->queryParams);
    }

    protected function removeBody()
    {
        unset($this->body);
    }

    protected function removeStream()
    {
        unset($this->stream);
    }
}
