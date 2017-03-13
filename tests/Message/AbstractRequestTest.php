<?php

namespace tests\Message;


use Omnipay\PlugNPay\Message\AbstractRequest;
use Omnipay\Tests\TestCase;

/**
 * Class AbstractRequestTest
 *
 * @package tests\Message
 */
abstract class AbstractRequestTest extends TestCase
{
    /** @var AbstractRequest */
    protected $request;
    /** @var string */
    protected $username;
    /** @var string */
    protected $password;

    /**
     * Setup the credentials
     *
     * This is configured to use PlugNPay's default test account. If you would like to use your own account copy the
     * pnpCredentials.json file into a myCredentials.json file in the tests directory and edit that file to have your
     * username and password.
     */
    public function setUp()
    {
        $myCredentialsFilePath = dirname(__FILE__) . '/../myCredentials.json';
        $pnpCredentialsFilePath = dirname(__FILE__) . '/../pnpCredentials.json';

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
    }

    /**
     * Get a valid card with the specific data needed to test on Plug 'N Pay
     *
     * @return array
     */
    protected function getTestableCard()
    {
        $card = $this->getValidCard();
        $card['expiryMonth'] = '01';
        $card['expiryYear'] = '19';
        $card['cvv'] = '810';

        return $card;
    }

    /**
     * Test the endpoint
     */
    public function testEndpoint()
    {
        $this->assertSame('https://pay1.plugnpay.com/payment/pnpremote.cgi', $this->request->getEndPoint());
    }

    /**
     * Test setting and retrieving the username
     */
    public function testUsername()
    {
        $this->assertSame($this->request, $this->request->setUsername($this->username));
        $this->assertSame($this->username, $this->request->getUsername());
    }

    /**
     * Test setting and retrieving the password
     */
    public function testPassword()
    {
        $this->assertSame($this->request, $this->request->setPassword($this->password));
        $this->assertSame($this->password, $this->request->getPassword());
    }
}