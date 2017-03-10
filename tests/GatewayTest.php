<?php

namespace tests;


use Omnipay\PlugNPay\Gateway;
use Omnipay\Tests\GatewayTestCase;

/**
 * Class GatewayTest
 * Test the functions in the Gateway object.
 * @package tests
 */
class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    protected $gateway;
    /** @var array */
    protected $authorizeOptions;
    /** @var string */
    protected $username;
    /** @var string */
    protected $password;

    /**
     * Setup the gateway for testing.
     *
     * This is configured to use PlugNPay's default test account. If you would like to use your own account copy the
     * pnpCredentials.json file into a myCredentials.json file in the tests directory and edit that file to have your
     * username and password.
     */
    public function setUp()
    {
        $myCredentialsFilePath = dirname(__FILE__) . '/myCredentials.json';
        $pnpCredentialsFilePath = dirname(__FILE__) . '/pnpCredentials.json';

        if(file_exists($myCredentialsFilePath)) {
            $credentialsJson = file_get_contents($myCredentialsFilePath);
        } else {
            $credentialsJson = file_get_contents($pnpCredentialsFilePath);
        }

        if($credentialsJson) {
            $credentials = json_decode($credentialsJson);
            $this->username = $credentials->username;
            $this->password = $credentials->password;
        }

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->authorizeOptions = [
            'amount'=>'10.00',
            'card'=>$this->getValidCard()
        ];
    }

    /**
     * Test that the correct name is coming back
     */
    public function testGetName()
    {
        $this->assertSame('Plug & Pay Remote Client', $this->gateway->getName());
    }

    /**
     * Test that the default parameters have not been altered
     */
    public function testGetDefaultParameters()
    {
        $parameterArray = [
            'username'=>'',
            'password'=>''
        ];

        $this->assertSame($parameterArray, $this->gateway->getDefaultParameters());
    }

    /**
     * Test setting and retrieving the username
     */
    public function testUsername()
    {
        $this->assertSame($this->gateway, $this->gateway->setUsername($this->username));
        $this->assertSame($this->username, $this->gateway->getUsername());
    }

    /**
     * Test setting and retrieving the password
     */
    public function testPassword()
    {
        $this->assertSame($this->gateway, $this->gateway->setPassword($this->password));
        $this->assertSame($this->password, $this->gateway->getPassword());
    }

    /**
     * Test the creation of an AuthorizeRequest object
     */
    public function testAuthorize()
    {
        $request = $this->gateway->authorize($this->authorizeOptions);
        $this->assertInstanceOf('\Omnipay\PlugNPay\Message\AuthorizeRequest', $request);
        $this->assertEquals('10.00', $request->getAmount());
        $this->assertInstanceOf('\Omnipay\Common\CreditCard', $request->getCard());
    }

    /**
     * Test the creation of an CaptureRequest object
     */
    public function testCapture()
    {
        $request = $this->gateway->capture([
            'transactionReference'=>'12345',
            'amount'=>'10.00'
        ]);
        $this->assertInstanceOf('\Omnipay\PlugNPay\Message\CaptureRequest', $request);
        $this->assertSame('12345', $request->getTransactionReference());
        $this->assertEquals('10.00', $request->getAmount());
    }

    /**
     * Test the creation of an PurchaseRequest object
     */
    public function testPurchase()
    {
        $request = $this->gateway->purchase($this->authorizeOptions);
        $this->assertInstanceOf('\Omnipay\PlugNPay\Message\PurchaseRequest', $request);
        $this->assertEquals('10.00', $request->getAmount());
        $this->assertInstanceOf('\Omnipay\Common\CreditCard', $request->getCard());
    }

    /**
     * Test the creation of an RefundRequest object
     */
    public function testRefund()
    {
        $request = $this->gateway->refund([
            'transactionReference'=>'12345',
            'amount'=>'10.00',
            'currency'=>'USD'
        ]);
        $this->assertInstanceOf('\Omnipay\PlugNPay\Message\RefundRequest', $request);
        $this->assertSame('12345', $request->getTransactionReference());
        $this->assertEquals('10.00', $request->getAmount());
        $this->assertSame('USD', $request->getCurrency());
    }

    /**
     * Test the creation of an VoidRequest object
     */
    public function testVoid()
    {
        $request = $this->gateway->void([
            'transactionReference'=>'12345',
            'amount'=>'10.00'
        ]);
        $this->assertInstanceOf('\Omnipay\PlugNPay\Message\VoidRequest', $request);
        $this->assertSame('12345', $request->getTransactionReference());
        $this->assertEquals('10.00', $request->getAmount());
        $requestData = $request->getData();
        $this->assertSame('auth', $requestData['txn-type']);
    }
}
