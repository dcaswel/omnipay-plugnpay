<?php

namespace Omnipay\PlugNPay\Message;

/**
 * Class PurchaseRequest
 *
 * @package Omnipay\PlugNPay\Message
 */
class PurchaseRequest extends AuthorizeRequest
{
    /** @var string Tells PlugNPay to mark it for settlement */
    protected $authType = 'authpostauth';
}