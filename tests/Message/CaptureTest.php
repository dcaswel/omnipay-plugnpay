<?php

namespace tests\Message;


use Omnipay\PlugNPay\Message\CaptureRequest;

/**
 * Class CaptureTest
 *
 * @package tests\Message
 */
class CaptureTest extends AbstractRequestTest
{
    /** @var CaptureRequest */
    protected $request;

    /**
     * Instantiate and initialize the CaptureRequest
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'username'=>$this->username,
            'password'=>$this->password,
            'transactionReference'=>'2017030922002529106',
            'amount'=>'10.00'
        ]);
    }

    /**
     * Test that the getData function is returning correctly
     */
    public function testGetData()
    {
        $expectedArray = [
            "publisher-name" => $this->username,
            "publisher-password" => $this->password,
            "mode" => "mark",
            "orderID" => "2017030922002529106",
            "card-amount" => "10.00"
        ];

        $this->assertSame($expectedArray, $this->request->getData());
    }

    /**
     * Test a successful Capture
     */
    public function testCaptureSuccess()
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\PlugNPay\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2017030922272427067 has been successfully marked for settlement.', $response->getMessage());
    }

    /**
     * Test a failed Capture
     */
    public function testCaptureFailure()
    {
        $this->setMockHttpResponse('CaptureFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf('Omnipay\PlugNPay\Message\Response', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('orderid was not marked successfully.  Transaction may not be marked.', $response->getMessage());
    }
}