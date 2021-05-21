<?php

namespace Drupal\commerce_redsys\PluginForm;

use Drupal\commerce_payment\Exception\PaymentGatewayException;
use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm as BasePaymentOffsiteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce_redsys\RedsysAPI as RedsysAPI;
use Drupal\Core\Config\ConfigFactoryInterface;

class RedsysPaymentForm extends BasePaymentOffsiteForm
{
  /**
   * The Config Factory.
   *
   * @var Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Class constructor.
   */
  public function __construct(ConfigFactoryInterface $config_factory)
  {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state)
  {
    $form = parent::buildConfigurationForm($form, $form_state);
    $config = $this->configFactory->get('commerce_redsys.configuracion');

    /** @var \Drupal\commerce_payment\Entity\PaymentInterface $payment */
    $payment = $this->entity;
    /** @var \Drupal\commerce_payplug\Plugin\Commerce\PaymentGateway\OffsiteOffsitePayPlug $payment_gateway_plugin */
    $payment_gateway_plugin = $payment->getPaymentGateway()->getPlugin();
    //$payment_gateway_configuration = $payment_gateway_plugin->getConfiguration();
    $red = new RedsysAPI;
    $urlOK = $form['#return_url'];
    $urlKO = $form['#cancel_url'];

    //estos dos valores los vamos cambiando en cada ejemplo
    $id = "0000" . $payment->getOrder()->id(); //el valor que le damos en cada ejemplo
    $amount = $payment->getAmount()->getNumber() * 100; //el valor que le damos en cada ejemplo

    $red->setParameter('DS_MERCHANT_AMOUNT', $amount);
    $red->setParameter('DS_MERCHANT_ORDER', $id);
    $red->setParameter('DS_MERCHANT_MERCHANTCODE', $config->get('fuc'));
    $red->setParameter('DS_MERCHANT_CURRENCY', $config->get('moneda'));
    $red->setParameter('DS_MERCHANT_TRANSACTIONTYPE', $config->get('trans'));
    $red->setParameter('DS_MERCHANT_TERMINAL', $config->get('terminal'));
    $red->setParameter('DS_MERCHANT_MERCHANTURL', $config->get('merchant_url'));
    $red->setParameter('DS_MERCHANT_URLOK', $urlOK);
    $red->setParameter('DS_MERCHANT_URLKO', $urlKO);

    $params = $red->createMerchantParameters();
    $clave = '';

    $signature = $red->createMerchantSignature($clave);
    $data = [
      'Ds_SignatureVersion' => $config->get('version'),
      'Ds_MerchantParameters' => $params,
      'Ds_Signature' => $signature
    ];

    foreach ($data as $name => $value) {
      if (isset($value)) {
        $form[$name] = ['#type' => 'hidden', '#value' => $value];
      }
    }
    return $this->buildRedirectForm($form, $form_state, $redirect_url, $data, self::REDIRECT_POST);
  }

  /**
   * {@inheritdoc}.
   */
  public function getFormId()
  {
    return 'redsys_payment_form';
  }
}
