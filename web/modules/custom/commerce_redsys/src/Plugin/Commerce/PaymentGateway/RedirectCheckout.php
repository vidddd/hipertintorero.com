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

/**
 * Provides the Redsys offsite Checkout payment gateway.
 *
 * @CommercePaymentGateway(
 *   id = "redsys_redirect_checkout",
 *   label = @Translation("Redys (Redirect to redsys)"),
 *   display_label = @Translation("Redsys"),
 *    forms = {
 *     "offsite-payment" = "Drupal\commerce_redsys\PluginForm\RedsysPaymentForm",
 *   },
 *   payment_method_types = {"credit_card"},
 *   credit_card_types = {
 *     "mastercard", "visa", "maestro"
 *   },
 * )
 */
class RedirectCheckout extends OffsitePaymentGatewayBase implements RedsysInterface
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
      'merchant_url' => '',
      'merchant_code' => '',
      'terminal' => '',
      'currency' => '',
      'transaction_type' => '0',
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

    $form['merchant_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Merchant Url'),
      '#description' => $this->t("The Url Merchant, to receive POST notifications"),
      '#default_value' => $this->configuration['merchant_url'],
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
      '#description' => $this->t("The Currency code"),
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
    return $form;
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
      $this->configuration['merchant_url'] = $values['merchant_url'];
      $this->configuration['merchant_code'] = $values['merchant_code'];
      $this->configuration['terminal'] = $values['terminal'];
      $this->configuration['currency'] = $values['currency'];
      $this->configuration['transaction_type'] = $values['transaction_type'];
    }
  }
  /**
   * {@inheritdoc}
   */
  public function onReturn(OrderInterface $order, Request $request)
  {

    $orderid = $order->id();
    if (empty($orderid)) {
      throw new PaymentGatewayException('Invoice id missing for this transaction.');
    }
    $this->logger->log('info', 'onReturn');
    // Common response processing for both redirect back and async notification.
    //$payment = $this->processFeedback($request);

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
    parent::onCancel($order, $request);
  }

  /**
   * {@inheritdoc}
   */
  public function onNotify(Request $request)
  {
    if (!$responseData = json_decode($request->getContent(), TRUE)) {
      // 1 = $request->getContent()
      // throw new PaymentGatewayException('Response data missing on notify, aborting.');
      throw new PaymentGatewayException(print_r($request->getPathInfo()));
    }

    $this->logger->log('info', 'onNotify2');
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
      throw new PaymentGatewayException('Bad feedback response, missing feedback parameter.');
    }

    // Get the payment method settings.
    $payment_method_settings = $this->getConfiguration();

    $red = new RedsysAPI;
    $logger = \Drupal::logger('commerce_redsys');
    $version = $request->query->get('Ds_SignatureVersion');
    $signature = $request->query->get('Ds_Signature');
    $params = $request->query->get('Ds_MerchantParameters');

    $des = $red->decodeMerchantParameters($params);

    $signatureCalculada = $red->createMerchantSignatureNotif($clave, $params);

    if ($signatureCalculada === $signature) {
      /*
         Ds_TransactionType:0;
          Ds_Card_Country:724;
          Ds_Card_Brand:1;
          Ds_Date:27/05/2019;
          Ds_SecurePayment:1;
          Ds_Order:000043;
          Ds_Hour:12:11;
          Ds_Response:0000;
          Ds_AuthorisationCode:041104;
          Ds_Currency:978;
          Ds_ConsumerLanguage:1;
          Ds_MerchantCode:285964623;
          Ds_Amount:17230;
          Ds_Terminal:003;
           */

      $codigoRespuesta = $red->getParameter("Ds_Response");
      $authcode = $red->getParameter("Ds_AuthorisationCode");
      $amount = $red->getParameter("Ds_Amount");
      $order = $red->getParameter("Ds_Order");
      $currency = $red->getParameter("Ds_Currency");

      $price = strval($amount / 100);
      //%echo $price; die;
      $payment_storage = $this->entityTypeManager->getStorage('commerce_payment');
      $payment = $payment_storage->create([
        'state' => 'complete',
        'amount' => new Price($price, "€"),
        'currency_code' => $currency,
        'payment_gateway' => $this->entityId,
        'order_id' => $order,
        'remote_id' => $authcode,
        'remote_state' => $codigoRespuesta,
      ]);

      $logger->info('Guardando informacion de Pago. Pedido:' . $order);

      $payment->save();
      drupal_set_message('El Pago fue recibido');

      $logger->info('informacion de pago guardada con exito ');

      return $payment;
    } else {
      drupal_set_message('Pago no completado. Error recibiendo datos del TPV', 'error');
    }
  }
}
