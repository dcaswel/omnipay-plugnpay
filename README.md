# Omnipay: Plug 'N Pay

**Plug 'N Pay driver for the Omnipay PHP payment processing library**

[![Source Code](http://img.shields.io/badge/source-CaswellWC/omnipay--plugnpay-blue.svg?style=flat-square)](https://github.com/CaswellWC/omnipay-plugnpay) [![Latest Version](https://img.shields.io/github/release/CaswellWC/omnipay-plugnpay.svg?style=flat-square)](https://github.com/CaswellWC/omnipay-plugnpay/releases) [![Software License](https://img.shields.io/github/license/CaswellWC/omnipay-plugnpay.svg?style=flat-square)](https://github.com/CaswellWC/omnipay-plugnpay/blob/master/LICENSE)

[![Build Status](https://img.shields.io/scrutinizer/build/g/CaswellWC/omnipay-plugnpay.svg?style=flat-square)](https://scrutinizer-ci.com/g/CaswellWC/omnipay-plugnpay/build-status/master) [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/CaswellWC/omnipay-plugnpay.svg?style=flat-square)](https://scrutinizer-ci.com/g/CaswellWC/omnipay-plugnpay/?branch=master) [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/CaswellWC/omnipay-plugnpay.svg?style=flat-square)](https://scrutinizer-ci.com/g/CaswellWC/omnipay-plugnpay/?branch=master) [![Total Downloads](https://img.shields.io/packagist/dt/CaswellWC/omnipay-plugnpay.svg?style=flat-square)](https://packagist.org/packages/CaswellWC/omnipay-plugnpay/)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Plug 'N Pay support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). This package is still in development and can not be installed via composer yet.

This package strives to use Semantic Versioning as explained [here](http://semver.org/).

## Basic Usage

The following gateways are provided by this package:

* PlugNPay

This package is still in development but will implement the following methods:

* ``authorize($options)`` – authorize an amount on the customer’s card.
* ``capture($options)`` – capture an amount you have previously authorized.
* ``purchase($options)`` – authorize and immediately capture an amount on the customer’s card.
* ``refund($options)`` – refund an already processed (settled) transaction.
* ``void($options)`` – reverse a previously authorized (unsettled) transaction.

For general usage instructions, please see the [Omnipay documentation](http://omnipay.thephpleague.com/).
For information on the parameters needed for each request, see the class documentation for that request in the Message folder.

### Basic Example

```php
use Omnipay\Omnipay;

// Setup payment gateway
$gateway = Omnipay::create('PlugNPay');
$gateway->setUsername('123456789');
$gateway->setPassword('abc123');

// Example form data
$formData = [
    'number'      => '4242424242424242',
    'expiryMonth' => '6',
    'expiryYear'  => '2016',
    'cvv'         => '123'
];

// Send purchase request
$response = $gateway->purchase([
    'amount'        => '10.00',
    'currency'      => 'USD',
    'transactionId' => '1234',
    'card'          => $formData
])->send();

// Process response
if ( $response->isSuccessful() )
{
    // Payment was successful
    print_r($response);
}
else
{
    // Payment failed
    echo $response->getMessage();
}
```

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/CaswellWC/omnipay-plugnpay/issues),
or better yet, fork the library and submit a pull request.
