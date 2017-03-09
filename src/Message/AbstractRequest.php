<?php

namespace Omnipay\PlugNPay\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    /** @var string Endpoint to send the data to */
    protected $endpoint = 'https://pay1.plugnpay.com/payment/pnpremote.cgi';
    /** @var string Tells PlugNPay what this request is doing. This should be overridden in each concrete class. */
    protected $mode;

    public function getEndPoint()
    {
        return $this->endpoint;
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    protected function generateResponse($httpResponse)
    {
        return $this->response = new Response($this, $httpResponse);
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post(
            $this->getEndPoint(),
            null,
            http_build_query($data)
        )->send();

        return $this->generateResponse($httpResponse);
    }

    public function getBaseData()
    {
        $this->validate('username', 'password');

        return [
            'publisher-name'=>$this->getUsername(),
            'publisher-password'=>$this->getPassword(),
            'mode'=>$this->mode
        ];
    }
}