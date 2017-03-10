<?php

namespace tests;


use Omnipay\PlugNPay\Gateway;
use Omnipay\Tests\TestCase;

/**
 * Class IntegrationTest
 *
 * @package tests
 */
class IntegrationTest extends TestCase
{
    /** @var Gateway */
    protected $gateway;
    /** @var string */
    protected $username;
    /** @var string */
    protected $password;

    /**
     * Instantiate the gateway and setup the credentials
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

        $this->gateway = new Gateway();
        $this->gateway->setUsername($this->username);
        $this->gateway->setPassword($this->password);
    }

    /**
     * Test an authorization followed by a capture of that authorization
     */
    public function testAuthorizeCapture()
    {
        $authResponse = $this->gateway->authorize([
            'amount'=>'10.00',
            'card'=>$this->getValidCard()
        ])->send();

        $this->assertTrue($authResponse->isSuccessful());
        $this->assertSame('success', $authResponse->getMessage());
        $this->assertEquals('00', $authResponse->getCode());

        $captureResponse = $this->gateway->capture([
            'transactionReference'=>$authResponse->getTransactionReference(),
            'amount'=>'10.00'
        ])->send();

        $this->assertTrue($captureResponse->isSuccessful());
        $this->assertSame($authResponse->getTransactionReference() . ' has been successfully marked for settlement.', $captureResponse->getMessage());
    }

    /**
     * Test a purchase followed by a refund of the purchase.
     */
    public function testPurchaseRefund()
    {
        $purchaseResponse = $this->gateway->purchase([
            'amount'=>'10.00',
            'card'=>$this->getValidCard(),
            'currency'=>'USD'
        ])->send();

        $this->assertTrue($purchaseResponse->isSuccessful());
        $this->assertSame('success', $purchaseResponse->getMessage());

        $refundResponse = $this->gateway->refund([
            'amount'=>'10.00',
            'transactionReference' => $purchaseResponse->getTransactionReference()
        ])->send();

        $this->assertTrue($refundResponse->isSuccessful());
        $this->assertSame('success', $refundResponse->getMessage());
    }

    /**
     * Test a purchase followed by a Void of that purchase. There does not seem to be a way to test a successful void in
     * a test environment with PlugNPay so that will come back as not successful.
     */
    public function testPurchaseVoid()
    {
        $purchaseResponse = $this->gateway->purchase([
            'amount'=>'20.00',
            'card'=> $this->getValidCard()
        ])->send();

        $this->assertTrue($purchaseResponse->isSuccessful());
        $this->assertSame('success', $purchaseResponse->getMessage());

        $voidResponse = $this->gateway->void([
            'amount'=>'20.00',
            'transactionReference'=>$purchaseResponse->getTransactionReference()
        ])->send();

        $this->assertFalse($voidResponse->isSuccessful());
        $this->assertSame('Transaction may not be voided.', $voidResponse->getMessage());
    }
}