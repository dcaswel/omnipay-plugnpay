<?php

namespace Omnipay\PlugNPay;


use Omnipay\Common\AbstractGateway;

/**
 * Class Gateway
 * Plug & Pay Remote Client Integration
 *
 * @package Omnipay\PlugNPay
 */
class Gateway extends AbstractGateway
{

    /**
     * @return string Gateway name.
     */
    public function getName()
    {
        return 'Plug & Pay Remote Client';
    }

    /**
     * @return array Default parameters.
     */
    public function getDefaultParameters()
    {
        return [
            'username'=>'',
            'password'=>''
        ];
    }

    public function authorize(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PlugNPay\Message\AuthorizeRequest', $parameters);
    }
}