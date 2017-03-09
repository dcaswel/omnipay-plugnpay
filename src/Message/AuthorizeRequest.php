<?php

namespace Omnipay\PlugNPay\Message;


use Omnipay\Common\Exception\InvalidCreditCardException;

class AuthorizeRequest extends AbstractRequest
{
    /** @var string Tells PlugNPay to not mark the transaction for settlement */
    protected $authType = 'authonly';
    /** @var string Tells PlugNPay that this is an authorization */
    protected $mode = 'auth';

    /**
     * Get the data needed for an authorization.
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('amount');
        $baseData = $this->getBaseData();
        $baseData['authtype'] = $this->authType;

        $card = $this->getCard();
        $card->validate();
        if(empty($card->getName())) {
            throw new InvalidCreditCardException('Name missing from credit card.');
        }

        $billingData = [
            'card-name'=>$card->getName(),
            'card-number'=>$card->getNumber(),
            'card-exp'=>$card->getExpiryDate('m/y'),
            'card-cvv'=>$card->getCvv(),
            'card-amount'=>$this->getAmount(),
            'currency'=>$this->getCurrency(),
            'card-address1'=>$card->getAddress1(),
            'card-address2'=>$card->getAddress2(),
            'card-city'=>$card->getCity(),
            'card-state'=>$card->getState(),
            'card-zip'=>$card->getPostcode(),
            'card-country'=>$card->getCountry()
        ];

        $shippingData = [
            'shipname'=>$card->getShippingName(),
            'address1'=>$card->getShippingAddress1(),
            'address2'=>$card->getShippingAddress2(),
            'city'=>$card->getShippingCity(),
            'state'=>$card->getShippingState(),
            'zip'=>$card->getShippingPostcode(),
            'country'=>$card->getShippingCountry()
        ];

        return array_merge($baseData, $billingData, $shippingData);

    }

    /**
     * The Authorize Request has a few extra methods in the response object.
     *
     * @param $httpResponse
     *
     * @return \Omnipay\PlugNPay\Message\AuthorizeResponse
     */
    public function generateResponse($httpResponse)
    {
        return $this->response = new AuthorizeResponse($this, $httpResponse);
    }
}