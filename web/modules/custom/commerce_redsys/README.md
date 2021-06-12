# Commerce Webpay

This module integrates Redsys spanish payment into the Drupal Commerce payment.

# Requirements

* Drupal Commerce and Commerce Payment

## Installation

Its recommend to download this module with composer https://getcomposer.org/, enable the module

## Configuration 

Create a Payment Gateway, and introduce the information of your merchant, FUC, merchant, currency, key, etc..

Administration > Commerce > Configuration > Payment gateways > Add payment gateway

You must active the HTTP notifications, in your admin TPV panel, otherwise the module not recive the payment and not complete the checkout.
