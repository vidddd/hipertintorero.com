<?php

namespace Drupal\tintorero\Resolvers;

use Drupal\commerce\Context;
use Drupal\commerce\PurchasableEntityInterface;
use Drupal\commerce_price\Price;
use Drupal\commerce_price\Resolver\PriceResolverInterface;

/**
 * Class OfertaResolver.
 *
 * @package Drupal\tintorero\Resolvers
 */
class OfertaResolver implements PriceResolverInterface
{

    /**
     * {@inheritdoc}
     */
    public function resolve(PurchasableEntityInterface $entity, $quantity, Context $context)
    {
        dump('999999999');
        die;
        // Make sure that product variation has a field called oferta price.
        if (!$entity->hasField('field_oferta')) {
            return;
        }

        if ($entity->get('field_oferta')->isEmpty()) {
            return;
        }

        /** @var \Drupal\commerce_price\Price $oferta_price */
        $oferta_price = $entity->get('field_oferta')->first()->toPrice();
        $oferta_price_number = $oferta_price->getNumber();
        $oferta_price_currency_code = $oferta_price->getCurrencyCode();

        if (!$oferta_price_number) {
            return;
        }

        return new Price($oferta_price_number, $oferta_price_currency_code);
    }
}
