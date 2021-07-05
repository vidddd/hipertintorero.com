<?php

namespace Drupal\commerce_redsys_payment\PluginForm;

use Drupal\commerce_payment\Exception\PaymentGatewayException;
use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm as BasePaymentOffsiteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce_redsys_payment\RedsysAPI as RedsysAPI;

class RedsysPaymentForm extends BasePaymentOffsiteForm
{

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state)
  {
    $form = parent::buildConfigurationForm($form, $form_state);
    $config = $this->getConfiguration();

    /** @var \Drupal\commerce_payment\Entity\PaymentInterface $payment */
    $payment = $this->entity;
    /** @var \Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayInterface $payment_gateway_plugin */
    $payment_gateway_plugin = $payment->getPaymentGateway()->getPlugin();
    try {
      $red = new RedsysAPI;
      $urlOK = $form['#return_url'];
      $urlKO = $form['#cancel_url'];

      $id = "0000" . $payment->getOrder()->id();
      $amount = $payment->getAmount()->multiply(100)->getNumber();

      // URL for receive HTTP notificacion after checkout
      $merchant_url = $payment_gateway_plugin->getNotifyUrl()->toString(); // .....payment/notify/__pluginname__

      $red->setParameter('DS_MERCHANT_AMOUNT', $amount);
      $red->setParameter('DS_MERCHANT_ORDER', $id);
      $red->setParameter('DS_MERCHANT_MERCHANTCODE', $config['merchant_code']);
      $red->setParameter('DS_MERCHANT_CURRENCY', $config['currency']);
      $red->setParameter('DS_MERCHANT_TRANSACTIONTYPE', $config['transaction_type']);
      $red->setParameter('DS_MERCHANT_TERMINAL', $config['terminal']);
      $red->setParameter('DS_MERCHANT_MERCHANTURL', $merchant_url);
      $red->setParameter('DS_MERCHANT_URLOK', $urlOK);
      $red->setParameter('DS_MERCHANT_URLKO', $urlKO);

      $params = $red->createMerchantParameters();

      $signature = $red->createMerchantSignature($config['signature']);

      $data = [
        'Ds_SignatureVersion' => $config['signatureversion'],
        'Ds_MerchantParameters' => $params,
        'Ds_Signature' => $signature
      ];
    } catch (\Exception $exception) {
      throw new PaymentGatewayException('Error Building Payment form.');
    }

    if ($config['mode'] == 'test') {
      $redirect_url = $config['url_test'];
    } else {
      $redirect_url = $config['url_live'];
    }

    return $this->buildRedirectForm($form, $form_state, $redirect_url, $data, self::REDIRECT_POST);
  }
  /**
   * @return array
   */
  private function getConfiguration()
  {
    /** @var \Drupal\commerce_payment\Entity\PaymentInterface $payment */
    $payment = $this->entity;

    /** @var \Drupal\commerce_quickpay_gateway\Plugin\Commerce\PaymentGateway\RedirectCheckout $payment_gateway_plugin */
    $payment_gateway_plugin = $payment->getPaymentGateway()->getPlugin();

    return $payment_gateway_plugin->getConfiguration();
  }

  /**
   * {@inheritdoc}.
   */
  public function getFormId()
  {
    return 'commerce_redsys_payment_form';
  }
}
