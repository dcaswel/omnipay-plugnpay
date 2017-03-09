<?php

namespace Omnipay\PlugNPay\Message;

/***
 * Class CaptureRequest
 *
 * @package Omnipay\PlugNPay\Message
 */
class CaptureRequest extends AbstractTransactionAdminRequest
{
    /** @var string Tells PlugNPay that this is a mark request */
    protected $mode = 'mark';
}