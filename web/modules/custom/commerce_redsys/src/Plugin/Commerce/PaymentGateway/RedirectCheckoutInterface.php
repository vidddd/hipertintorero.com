<?php

namespace Drupal\commerce_redsys\Plugin\Commerce\PaymentGateway;

use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides the interface for the Redsys payment gateway.
 */
interface RedirectCheckoutInterface extends OffsitePaymentGatewayInterface
{

    /**
     * Common response for all notificacions, from Redsys
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *   The request.
     *
     * @return \Drupal\commerce_payment\Entity\PaymentInterface|null
     *   The payment entity, or NULL in case of an exception.
     *
     */
    public function processFeedback(Request $request);

    
}
