<?php

namespace Omnipay\PlugNPay\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

class AbstractRequest extends BaseAbstractRequest
{

    protected $endpoint = 'https://pay1.plugnpay.com/payment/pnpremote.cgi';

    public function getEndPoint()
    {
        return $this->endpoint;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post(
            $this->getEndPoint(),
            null,
            http_build_query($data)
        )->send();

    }
}