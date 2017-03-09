<?php

namespace Omnipay\PlugNPay\Message;

/**
 * Class VoidRequest
 *
 * @package Omnipay\PlugNPay\Message
 */
class VoidRequest extends AbstractTransactionAdminRequest
{
    /** @var string Tells PlugNPay to do a void */
    protected $mode = 'void';

    /**
     * Voids also have a txn-type which they say to always set to 'auth'
     *
     * @return array
     */
    public function getData()
    {
        $voidData = [
            'txn-type'=>'auth'
        ];

        return array_merge($voidData, parent::getData());
    }
}