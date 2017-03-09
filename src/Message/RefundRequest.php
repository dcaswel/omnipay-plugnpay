<?php

namespace Omnipay\PlugNPay\Message;


/**
 * Class RefundRequest
 *
 * @package Omnipay\PlugNPay\Message
 */
class RefundRequest extends AbstractRequest
{
    /** @var string Tells PlugNPay that this is a return */
    protected $mode = 'return';

    /**
     * The return also includes the currency which may be required if your account has multicurrency.
     * @return array
     */
    public function getData()
    {
        $refundData = [
            'currency'=>$this->getCurrency()
        ];

        return array_merge($refundData, parent::getData());
    }
}