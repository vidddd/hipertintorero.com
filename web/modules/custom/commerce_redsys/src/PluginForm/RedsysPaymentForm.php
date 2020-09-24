<?php
namespace Drupal\commerce_redsys\PluginForm;

use Drupal\commerce_payment\Exception\PaymentGatewayException;
use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm as BasePaymentOffsiteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce_redsys\RedsysAPI as RedsysAPI;

class RedsysPaymentForm extends BasePaymentOffsiteForm {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    /** @var \Drupal\commerce_payment\Entity\PaymentInterface $payment */
    $payment = $this->entity;
    /** @var \Drupal\commerce_payplug\Plugin\Commerce\PaymentGateway\OffsiteOffsitePayPlug $payment_gateway_plugin */
    $payment_gateway_plugin = $payment->getPaymentGateway()->getPlugin();
    $payment_gateway_configuration = $payment_gateway_plugin->getConfiguration();
    $red = new RedsysAPI;
    // Valores de entrada que no hemos cmbiado para ningun ejemplo
  	$fuc="285964623";
  	$terminal="003";
  	$moneda="978";
  	$trans="0";
  	$url="";
  	$urlOK = $form['#return_url'];
    $urlKO = $form['#cancel_url'];

  	//estos dos valores los vamos cambiando en cada ejemplo
  	$id="0000".$payment->getOrder()->id();//el valor que le damos en cada ejemplo
  	$amount=$payment->getAmount()->getNumber() * 100;//el valor que le damos en cada ejemplo

    $red->setParameter('DS_MERCHANT_AMOUNT',$amount);
    $red->setParameter('DS_MERCHANT_ORDER',$id);
    $red->setParameter('DS_MERCHANT_MERCHANTCODE',$fuc);
    $red->setParameter('DS_MERCHANT_CURRENCY',$moneda);
    $red->setParameter('DS_MERCHANT_TRANSACTIONTYPE',$trans);
    $red->setParameter('DS_MERCHANT_TERMINAL',$terminal);
    $red->setParameter('DS_MERCHANT_MERCHANTURL',$url);
    $red->setParameter('DS_MERCHANT_URLOK', $urlOK);
    $red->setParameter('DS_MERCHANT_URLKO', $urlKO);

    $version="HMAC_SHA256_V1";
    $params = $red->createMerchantParameters();
    $clave = 'he5YgpkWQYKw0byM0j+dvdVhbN4st75q';

    $signature = $red->createMerchantSignature($clave);
    $data = [
      'Ds_SignatureVersion' => $version,
      'Ds_MerchantParameters' => $params,
      'Ds_Signature' => $signature
    ];

    foreach ($data as $name => $value) {
      if (isset($value)) {
        $form[$name] = ['#type' => 'hidden', '#value' => $value];
      }
    }
  //  $redirect_url = 'https://sis-t.redsys.es:25443/sis/realizarPago';
     $redirect_url = 'https://sis.redsys.es/sis/realizarPago';
    return $this->buildRedirectForm($form, $form_state, $redirect_url, $data, self::REDIRECT_POST);
  }

}
