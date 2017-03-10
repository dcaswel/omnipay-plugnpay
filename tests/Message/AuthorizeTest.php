<?php

namespace tests\Message;


use Omnipay\PlugNPay\Message\AuthorizeRequest;
use Omnipay\PlugNPay\Message\AuthorizeResponse;

/**
 * Class AuthorizeTest
 *
 * @package tests\Message
 */
class AuthorizeTest extends AbstractRequestTest
{

    /** @var AuthorizeRequest */
    protected $request;

    /**
     * Instantiate and initialize the request
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
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
     * Make sure getData is returning what it should
     */
    public function testGetData()
    {
        $expectedData = [
            "publisher-name" => $this->username,
            "publisher-password" => $this->password,
            "mode" => "auth",
            "authtype" => "authonly",
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
     * Test a successful Authorize
     */
    public function testAuthorizeSuccess()
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
     * Test a failed Authorize
     */
    public function testAuthorizeFailure()
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