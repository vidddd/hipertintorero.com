<?php

namespace Drupal\commerce_shipping_hipertintorero\Plugin\Commerce\ShippingMethod;

use Drupal\commerce_price\Price;
use Drupal\commerce_shipping\Entity\ShipmentInterface;
use Drupal\commerce_shipping\ShippingRate;
use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce_shipping\Plugin\Commerce\ShippingMethod\FlatRate;
use Drupal\commerce_shipping\Plugin\Commerce\ShippingMethod\ShippingMethodBase;

/**
 * Provides the PesoTotal shipping method.
 *
 * @CommerceShippingMethod(
 *   id = "peso_total",
 *   label = @Translation("Calcula los costes de envio segun el peso total del pedido hipertintoero.com"),
 * )
 */
class PesoTotal extends FlatRate
{

    /**
     * {@inheritdoc}
     */
    public function buildConfigurationForm(array $form, FormStateInterface $form_state)
    {
        $form = parent::buildConfigurationForm($form, $form_state);
        $form['peso_total']['#description'] = t('Calculo en base a peso total del pedido para hipertintorero.com.');
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function calculateRates(ShipmentInterface $shipment)
    {
        if ($shipment->getShippingProfile()->address->isEmpty()) {
            return [];
        }
        $peso = 0;
        $quantity = 0;
        $total = 0;
        foreach ($shipment->getItems() as $shipment_item) {
            $total += $shipment_item->getDeclaredValue()->getNumber();
            $unit = $shipment_item->getWeight()->getUnit();
            if ($unit != 'kg') {
                $peso += $shipment_item->getWeight()->convert('kg')->getNumber();
            } else {
                $peso += $shipment_item->getWeight()->getNumber();
            }
        }
        if ((int) $total < 500) { // si el pedido es mas de 500 coste de envio = 0
            if ($peso < 5)
                $quantity = 7.7;
            else if ($peso < 10)
                $quantity = 8.10;
            else if ($peso < 20)
                $quantity = 9.93;
            else if ($peso < 30)
                $quantity = 11.84;
            else if ($peso < 40)
                $quantity = 12.66;
            else if ($peso < 50)
                $quantity = 14.24;
            else if ($peso < 60)
                $quantity = 16.52;
            else if ($peso < 70)
                $quantity = 17.56;
            else if ($peso < 80)
                $quantity = 21.10;
            else if ($peso < 90)
                $quantity = 22.35;
            else if ($peso < 100)
                $quantity = 24.61;
            else if ($peso < 150)
                $quantity = 30.28;
            else if ($peso < 200)
                $quantity = 38.97;
            else if ($peso < 300)
                $quantity = 48.11;
            else if ($peso < 400)
                $quantity = 60.24;
            else if ($peso < 500)
                $quantity = 72.75;
            else if ($peso < 600)
                $quantity = 78.66;
            else if ($peso < 700)
                $quantity = 88.43;
            else if ($peso < 800)
                $quantity = 95.90;
            else if ($peso < 1000)
                $quantity = 120;
        }
        //$rate_id = 0;
        $definition = [];
        // sumamos el 21 % de iva al precio total del porte
        $quantity =  number_format($quantity * 1.21, 2);

        $amount = $this->configuration['rate_amount'];
        $amount = new Price($amount['number'], $amount['currency_code']);
        $amount = $amount->multiply((string) $quantity);
        //$definition['shipping_method_id'] = 0;
        //$definition['service'] = $this->services['default'];
        //$definition['amount'] = $amount;
        //$rates[] = new ShippingRate($definition);
        $rates[] = new ShippingRate([
            'shipping_method_id' => $this->parentEntity->id(),
            'service' => $this->services['default'],
            'amount' => $amount,
        ]);

        return $rates;
    }
}
