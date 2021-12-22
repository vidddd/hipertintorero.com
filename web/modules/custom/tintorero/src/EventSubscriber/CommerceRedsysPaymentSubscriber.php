<?php

namespace Drupal\tintorero\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\commerce_redsys_payment\Event\CommerceRedsysPaymentEvent;

/**
 * 
 */
class CommerceRedsysPaymentSubscriber implements EventSubscriberInterface
{

    /**
     * Log the creation of a new node.
     *
     * @param \Drupal\commerce_redsys_payment\Event\CommerceRedsysPaymentEvent $event
     */
    public function onOnpaymentAythorized(CommerceRedsysPaymentEvent $event)
    {
        $order = $event->geOrder();
        \Drupal::logger('tintorero')->notice(
            'On Payment Authorized: @order_id',
            array(
                '@type' => $order->id()
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        $events[CommerceRedsysPaymentEvent::PAYMENT_AUTHORIZED][] = ['onPaymentAuthorized'];
        return $events;
    }
}
