<?php

namespace Drupal\commerce_redsys\Plugin\Commerce\PaymentGateway;

use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\commerce_payment\Exception\DeclineException;
use Drupal\commerce_payment\Exception\InvalidResponseException;
use Drupal\commerce_payment\PaymentMethodTypeManager;
use Drupal\commerce_payment\PaymentTypeManager;
use Symfony\Component\HttpFoundation\Request;
use Drupal\commerce_redsys\RedsysAPI as RedsysAPI;
use Drupal\commerce_price\Price;
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
class RedirectCheckout extends OffsitePaymentGatewayBase implements RedsysInterface {

  public function defaultConfiguration() {
     return [
         'signatureversion' => '',
         'url_pruebas' => '',
         'url_real' => '',
       ] + parent::defaultConfiguration();
   }

   public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
     $form = parent::buildConfigurationForm($form, $form_state);

     $form['signatureversion'] = [
       '#type' => 'textfield',
       '#title' => $this->t('Signature version'),
       '#description' => $this->t(""),
       '#default_value' => $this->configuration['signatureversion'],
     ];

     $form['url_pruebas'] = [
       '#type' => 'textfield',
       '#title' => $this->t('URL Pruebas'),
       '#description' => $this->t(""),
       '#default_value' => $this->configuration['url_real'],
     ];

     $form['url_real'] = [
       '#type' => 'textfield',
       '#title' => $this->t('URL Real'),
       '#description' => $this->t(""),
       '#default_value' => $this->configuration['url_pruebas'],
     ];
     return $form;
   }

   public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
     parent::submitConfigurationForm($form, $form_state);
     if (!$form_state->getErrors()) {
       $values = $form_state->getValue($form['#parents']);
       $this->configuration['signatureversion'] = $values['signatureversion'];
       $this->configuration['url_pruebas'] = $values['url_pruebas'];
       $this->configuration['url_real'] = $values['url_real'];
     }
   }
   /**
      * {@inheritdoc}
      */
    public function onReturn(OrderInterface $order, Request $request) {

      $orderid = $order->id();
      if (empty($orderid)) {
        throw new PaymentGatewayException('Invoice id missing for this transaction.');
      }

      $logger = \Drupal::logger('commerce_redsys');
      $logger->notice('Hemos recibido tu pago');

      // Common response processing for both redirect back and async notification.
      $payment = $this->processFeedback($request);

      // Do not update payment state here - it should be done from the received
      // notification only, and considering that usually notification is received
      // even before the user returns from the off-site redirect, at this point
      // the state tends to be already the correct one.


     }

     /**
      * {@inheritdoc}
      */
     public function onCancel(OrderInterface $order, Request $request) {
       //parent::onCancel($order, $request);
       $this->messenger()->addMessage($this->t('Has cancelado el @gateway.', [
     '@gateway' => $this->getDisplayLabel(),
   ]));
     }

     /**
      * {@inheritdoc}
      */
     public function onNotify(Request $request) {
        parent::onNotify($request);
     }

     /**
      * Common response processing for both redirect back and async notification.
      *
      * @param \Symfony\Component\HttpFoundation\Request $request
      *   The request.
      *
      * @return \Drupal\commerce_payment\Entity\PaymentInterface|null
      *   The payment entity, or NULL in case of an exception.
      *
      * @throws InvalidResponseException
      *   An exception thrown if response SHASign does not validate.
      * @throws DeclineException
      *   An exception thrown if payment has been declined.
      */
     private function processFeedback(Request $request) {

       $clave = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
       $red = new RedsysAPI;       $logger = \Drupal::logger('commerce_redsys');
       $version = $request->query->get('Ds_SignatureVersion');
       $signature = $request->query->get('Ds_Signature');
       $params = $request->query->get('Ds_MerchantParameters');

       $des = $red->decodeMerchantParameters($params);

       $signatureCalculada = $red->createMerchantSignatureNotif($clave,$params);

       if($signatureCalculada === $signature) {
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

         $logger->info('Guardando informacion de Pago. Pedido:'.$order );

         $payment->save();
         drupal_set_message('El Pago fue recibido');

         $logger->info('informacion de pago guardada con exito ');

         /*$ecommercePaymentResponse = new EcommercePaymentResponse($request->query->all());

         // Load the payment entity created in
         // ECommerceOffsiteForm::buildConfigurationForm().

         $payment = $this->entityTypeManager->getStorage('commerce_payment')->load($request->query->get('PAYMENT_ID'));

         $payment->setRemoteId($ecommercePaymentResponse->getParam('PAYID'));
         $payment->setRemoteState($ecommercePaymentResponse->getParam('STATUS'));
         $payment->save();

         // Validate response's SHASign.
         $passphrase = new Passphrase($this->configuration['sha_out']);
         $sha_algorithm = new HashAlgorithm($this->configuration['sha_algorithm']);
         $shaComposer = new AllParametersShaComposer($passphrase, $sha_algorithm);
         if (!$ecommercePaymentResponse->isValid($shaComposer)) {
           $payment->set('state', 'failed');
           $payment->save();
           throw new InvalidResponseException($this->t('The gateway response looks suspicious.'));
         }

         // Validate response's status.
         if (!$ecommercePaymentResponse->isSuccessful()) {
           $payment->set('state', 'failed');
           $payment->save();
           throw new DeclineException($this->t('Payment has been declined by the gateway (@error_code).', [
             '@error_code' => $ecommercePaymentResponse->getParam('NCERROR'),
           ]), $ecommercePaymentResponse->getParam('NCERROR'));
         }

         */
           return $payment;

       } else {
               drupal_set_message('Pago no completado. Error recibiendo datos del TPV', 'error');
       }


     }
}
