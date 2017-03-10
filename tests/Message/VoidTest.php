<?php

namespace tests\Message;


use Omnipay\PlugNPay\Message\VoidRequest;

/**
 * Class VoidTest
 *
 * @package tests\Message
 */
class VoidTest extends AbstractRequestTest
{
    /** @var VoidRequest */
    protected $request;

    /**
     * Instantiate and initialize the VoidRequest
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'username'=>$this->username,
            'password'=>$this->password,
            'transactionReference'=>'2017030922582808676',
            'amount'=>'10.00'
        ]);
    }

    /**
     * Test that the getData method is returning the correct data
     */
    public function testGetData()
    {
        $expectedArray = [
            'txn-type' => 'auth',
            'publisher-name' => $this->username,
            'publisher-password' => $this->password,
            'mode' => 'void',
            'orderID' => '2017030922582808676',
            'card-amount' => '10.00'
        ];

        $this->assertSame($expectedArray, $this->request->getData());
    }

    /**
     * Test a successful Void
     */
    public function testVoidSuccess()
    {
        $this->setMockHttpResponse('VoidSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\PlugNPay\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2017030922582808676 has been successfully voided.', $response->getMessage());
    }

    /**
     * Test a failed Void
     */
    public function testVoidFailure()
    {
        $this->setMockHttpResponse('VoidFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\PlugNPay\Message\Response', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Transaction may not be voided.', $response->getMessage());
    }
}