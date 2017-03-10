<?php

namespace tests\Message;


use Omnipay\PlugNPay\Message\RefundRequest;

/**
 * Class RefundTest
 *
 * @package tests\Message
 */
class RefundTest extends AbstractRequestTest
{
    /** @var RefundRequest */
    protected $request;

    /**
     * Instantiate and initialize the RefundRequest
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'username'=>$this->username,
            'password'=>$this->password,
            'transactionReference'=>'2017030922360419888',
            'amount'=>'10.00'
        ]);
    }

    /**
     * Test that the getData method is returning the correct data
     */
    public function testGetData()
    {
        $expectedArray = [
            "currency" => null,
            "publisher-name" => $this->username,
            "publisher-password" => $this->password,
            "mode" => "return",
            "orderID" => "2017030922360419888",
            "card-amount" => "10.00"
        ];

        $this->assertSame($expectedArray, $this->request->getData());
    }

    /**
     * Test a successful Refund
     */
    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\PlugNPay\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('success', $response->getMessage());
    }

    /**
     * Test a failed Refund
     */
    public function testRefundFailure()
    {
        $this->setMockHttpResponse('RefundFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\PlugNPay\Message\Response', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Transaction not yet settled.', $response->getMessage());
    }
}