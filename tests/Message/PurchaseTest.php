<?php

namespace tests\Message;


use Omnipay\PlugNPay\Message\PurchaseRequest;
use Omnipay\PlugNPay\Message\AuthorizeResponse;

/**
 * Class PurchaseTest
 *
 * @package tests\Message
 */
class PurchaseTest extends AbstractRequestTest
{
    /** @var PurchaseRequest */
    protected $request;

    /**
     * Instantiate and initialize the PurchaseRequest
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $card = $this->getValidCard();
        $card['expiryMonth'] = '01';
        $card['expiryYear'] = '19';
        $card['cvv'] = '810';
        $this->request->initialize([
            'username'=>$this->username,
            'password'=>$this->password,
            'amount'=>'10.00',
            'card'=>$card
        ]);
    }

    /**
     * Test that the getData is returning the correct data
     */
    public function testGetData()
    {
        $expectedData = [
            "publisher-name" => $this->username,
            "publisher-password" => $this->password,
            "mode" => "auth",
            "authtype" => "authpostauth",
            "card-name" => "Example User",
            "card-number" => "4111111111111111",
            "card-exp" => "01/19",
            "card-cvv" => "810",
            "card-amount" => "10.00",
            "currency" => null,
            "card-address1" => "123 Billing St",
            "card-address2" => "Billsville",
            "card-city" => "Billstown",
            "card-state" => "CA",
            "card-zip" => "12345",
            "card-country" => "US",
            "shipname" => "Example User",
            "address1" => "123 Shipping St",
            "address2" => "Shipsville",
            "city" => "Shipstown",
            "state" => "NY",
            "zip" => "54321",
            "country" => "US"
        ];

        $this->assertSame($expectedData, $this->request->getData());
    }

    /**
     * Test a successful Purchase
     */
    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');
        /** @var AuthorizeResponse $response */
        $response = $this->request->send();
        $this->assertInstanceOf('Omnipay\PlugNPay\Message\AuthorizeResponse', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('success', $response->getMessage());
        $this->assertEquals('00', $response->getCode());
        $this->assertSame('TSTAUT', $response->getAuthCode());
        $this->assertEquals('2017030920284225172', $response->getTransactionReference());
    }

    /**
     * Test a failed Purchase
     */
    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('AuthorizeFailure.txt');
        /** @var AuthorizeResponse $response */
        $response = $this->request->send();
        $this->assertInstanceOf('Omnipay\PlugNPay\Message\AuthorizeResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Insufficient Funds', $response->getMessage());
        $this->assertEquals('200', $response->getCode());
        $this->assertEmpty($response->getAuthCode());
        $this->assertEquals('2017030921382201653', $response->getTransactionReference());
    }
}