<?php

namespace Drupal\commerce_redsys\Plugin\Commerce\PaymentGateway;

use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce_order\Entity\OrderInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\commerce_payment\Exception\PaymentGatewayException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\commerce_payment\PaymentTypeManager;
use Drupal\commerce_payment\PaymentMethodTypeManager;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\commerce_redsys\RedsysAPI as RedsysAPI;
use Drupal\commerce_price\Price;

/**
 * Provides the Drupal Commerce Redsys offsite redirect payment gateway.
 *
 * @CommercePaymentGateway(
 *   id = "redsys_redirect_checkout",
 *   label = @Translation("Redys (Redirect to redsys)"),
 *   display_label = @Translation("Redsys"),
 *    forms = {
 *     "offsite-payment" = "Drupal\commerce_redsys\PluginForm\RedsysPaymentForm",
 *   },
 *   payment_method_types = {"credit_card"},
 *   
 * )
 */
class RedirectCheckout extends OffsitePaymentGatewayBase
{
  /**
   * The logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\commerce_payment\PaymentTypeManager $payment_type_manager
   *   The payment type manager.
   * @param \Drupal\commerce_payment\PaymentMethodTypeManager $payment_method_type_manager
   *   The payment method type manager.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, PaymentTypeManager $payment_type_manager, PaymentMethodTypeManager $payment_method_type_manager, TimeInterface $time, LoggerInterface $logger)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $payment_type_manager, $payment_method_type_manager, $time);

    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('plugin.manager.commerce_payment_type'),
      $container->get('plugin.manager.commerce_payment_method_type'),
      $container->get('datetime.time'),
      $container->get('logger.channel.commerce_redsys')
    );
  }
  public function defaultConfiguration()
  {
    return [
      'url_test' => '',
      'url_live' => '',
      'signatureversion' => '',
      'signature' => '',
      'merchant_code' => '',
      'terminal' => '',
      'currency' => '',
      'transaction_type' => '0',
      'debug_log' => null
    ] + parent::defaultConfiguration();
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state)
  {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['url_test'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL Test'),
      '#description' => $this->t("The url for test environment"),
      '#default_value' => $this->configuration['url_test'],
      '#required' => TRUE,
    ];

    $form['url_live'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL Live'),
      '#description' => $this->t("The Url for live environment"),
      '#default_value' => $this->configuration['url_live'],
      '#required' => TRUE,
    ];

    $form['signatureversion'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Signature version'),
      '#description' => $this->t("Signature Version encoding"),
      '#default_value' => $this->configuration['signatureversion'],
      '#required' => TRUE,
    ];

    $form['signature'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Signature'),
      '#description' => $this->t("Signature string encoding"),
      '#default_value' => $this->configuration['signature'],
      '#required' => TRUE,
    ];

    $form['merchant_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Merchant Code'),
      '#description' => $this->t("Merchant Code"),
      '#default_value' => $this->configuration['merchant_code'],
      '#required' => TRUE,
    ];

    $form['terminal'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Terminal'),
      '#description' => $this->t("Terminal Code"),
      '#default_value' => $this->configuration['terminal'],
      '#required' => TRUE,
    ];

    $form['currency'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Currency'),
      '#description' => $this->t("The Currency code, ISO4217 format"),
      '#default_value' => $this->configuration['currency'],
      '#required' => TRUE,
    ];

    $form['transaction_type'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Transaction Type'),
      '#description' => $this->t("Transaction Type"),
      '#default_value' => $this->configuration['transaction_type'],
      '#required' => TRUE,
    ];
    $form['debug_log'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable verbose logging for debugging.'),
      '#return_value' => '1',
      '#default_value' => $this->configuration['debug_log'],
    ];
    $form['mode']['#access'] = FALSE;
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateConfigurationForm($form, $form_state);

    // TODO: validate urls, etc...
  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitConfigurationForm($form, $form_state);
    if (!$form_state->getErrors()) {
      $values = $form_state->getValue($form['#parents']);
      $this->configuration['url_test'] = $values['url_test'];
      $this->configuration['url_live'] = $values['url_live'];
      $this->configuration['signatureversion'] = $values['signatureversion'];
      $this->configuration['signature'] = $values['signature'];
      $this->configuration['merchant_code'] = $values['merchant_code'];
      $this->configuration['terminal'] = $values['terminal'];
      $this->configuration['currency'] = $values['currency'];
      $this->configuration['transaction_type'] = $values['transaction_type'];
      $this->configuration['debug_log'] = $values['debug_log'];
    }
  }
  /**
   * {@inheritdoc}
   */
  public function onReturn(OrderInterface $order, Request $request)
  {

    $orderid = $order->id();
    if (empty($orderid)) {
      throw new PaymentGatewayException($this->t('Invoice id missing for this transaction.'));
    }
    $this->logger->log('info', 'onReturn');

    // Do not update payment state here - it should be done from the received
    // notification only, and considering that usually notification is received
    // even before the user returns from the off-site redirect, at this point
    // the state tends to be already the correct one

  }

  /**
   * {@inheritdoc}
   */
  public function onCancel(OrderInterface $order, Request $request)
  {
    $this->logger->info('The user canceled payment process for order %order_id', [
      '%order_id' => $order->id(),
    ]);
    parent::onCancel($order, $request);
  }

  /**
   * {@inheritdoc}
   */
  public function onNotify(Request $request)
  {

    if ($this->debugEnabled()) {
      $this->logger->debug(print_r($request->getContent(), TRUE));
    }

    if (!$responseData = $request->getContent()) {
      throw new PaymentGatewayException($this->t('Response data from TPV missing, aborting.'));
    }

    $this->processFeedback($request);
  }

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
  private function processFeedback(Request $request)
  {

    $params = [
      'Ds_SignatureVersion' => $request->get('Ds_SignatureVersion'),
      'Ds_MerchantParameters' => $request->get('Ds_MerchantParameters'),
      'Ds_Signature' => $request->get('Ds_Signature'),
    ];

    if (empty($params['Ds_SignatureVersion']) || empty($params['Ds_MerchantParameters']) || empty($params['Ds_Signature'])) {
      throw new PaymentGatewayException($this->t('Bad feedback response, missing feedback parameter.'));
    }

    // Get the payment method settings.
    $payment_method_settings = $this->getConfiguration();

    $red = new RedsysAPI;
    // $version = $params['Ds_SignatureVersion']; not in use
    $ds_signature = $params['Ds_Signature'];
    $params = $params['Ds_MerchantParameters'];
    $signature = $payment_method_settings['signature'];
    $red->decodeMerchantParameters($params);

    $signatureCalc = $red->createMerchantSignatureNotif($signature, $params);

    if ($signatureCalc === $ds_signature) {

      $DsResponse = $red->getParameter("Ds_Response");


      if ($this->debugEnabled()) {
        if ($DsErrorCode = $red->getParameter("Ds_ErrorCode"))
          $this->logger->debug("DsErrorCode " . print_r($DsErrorCode, TRUE));
        if ($DsResponse = $red->getParameter("Ds_Response"))
          $this->logger->debug("DsResponse " . print_r($DsResponse, TRUE));
      }

      if ($DsResponse == '0000') {

        $authcode = $red->getParameter("Ds_AuthorisationCode");
        $amount = $red->getParameter("Ds_Amount");
        $order = $red->getParameter("Ds_Order");
        $currency = $red->getParameter("Ds_Currency");

        $price = strval($amount / 100);

        $payment_storage = $this->entityTypeManager->getStorage('commerce_payment');
        $payment = $payment_storage->create([
          'state' => 'complete',
          'amount' => new Price($price, "€"),
          'currency_code' => $currency,
          'payment_gateway' => $this->entityId,
          'order_id' => $order,
          'remote_id' => $authcode,
          'remote_state' => $DsResponse,
          'authorized' => $this->time->getRequestTime(),
        ]);

        $payment->save();
        \Drupal::messenger()->addStatus($this->t('The payment is received, thank you'));

        return $payment;
      }
    } else {
      \Drupal::messenger()->addError($this->t('No payment received, please try again or select diferent payment method'));
    }
  }

  /**
   * Check if verbose logging enabled.
   *
   * @return bool
   *   Whether debugging is enabled or not.
   */
  protected function debugEnabled()
  {
    return $this->configuration['debug_log'] == 1 ? TRUE : FALSE;
  }
}
