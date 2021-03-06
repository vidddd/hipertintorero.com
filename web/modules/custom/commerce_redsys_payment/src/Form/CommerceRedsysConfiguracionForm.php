<?php

namespace Drupal\commerce_redsys_payment\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */

class CommerceRedsysConfiguracionForm extends ConfigFormBase
{

    public function getFormId()
    {
        return 'tintorero_configuracion';
    }

    protected function getEditableConfigNames()
    {
        return [
            'commerce_redsys_payment.configuracion',
        ];
    }

    public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL)
    {
        $config = $this->config('commerce_redsys_payment.configuracion');

        $form['configuracion'] = array(
            '#type'  => 'fieldset',
            '#title' => $this->t('Configuraciones de La Pasarela de Pago Commerce Redsys'),
        );
        $form['configuracion']['merchant_url'] = array(
            '#type'          => 'textfield',
            '#title'         => 'Merchant Url',
            '#default_value' => $config->get('merchant_url'),
        );
        $form['configuracion']['redirect_url'] = array(
            '#type'          => 'textfield',
            '#title'         => 'Redirect Url',
            '#default_value' => $config->get('redirect_url'),
        );
        $form['configuracion']['fuc'] = array(
            '#type'          => 'textfield',
            '#title'         => 'Fuc',
            '#default_value' => $config->get('fuc'),
        );
        $form['configuracion']['moneda'] = array(
            '#type'          => 'textfield',
            '#title'         => 'Moneda',
            '#default_value' => $config->get('moneda'),
        );
        $form['configuracion']['trans'] = array(
            '#type'          => 'textfield',
            '#title'         => 'Trans',
            '#default_value' => $config->get('trans'),
        );
        $form['configuracion']['version'] = array(
            '#type'          => 'textfield',
            '#title'         => 'Version',
            '#default_value' => $config->get('version'),
        );
        $form['configuracion']['terminal'] = array(
            '#type'          => 'textfield',
            '#title'         => 'Fuc',
            '#default_value' => $config->get('terminal'),
        );
        $form['configuracion']['clave'] = array(
            '#type'          => 'textfield',
            '#title'         => 'Clave',
            '#default_value' => $config->get('clave'),
        );
        $form['configuracion']['terminal'] = array(
            '#type'          => 'textfield',
            '#title'         => 'Terminal',
            '#default_value' => $config->get('terminal'),
        );
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('commerce_redsys_payment.configuracion')
            ->set('merchant_url', $form_state->getValue('merchant_url'))
            ->set('redirect_url', $form_state->getValue('redirect_url'))
            ->set('fuc', $form_state->getValue('fuc'))
            ->set('version', $form_state->getValue('trans'))
            ->set('version', $form_state->getValue('version'))
            ->set('clave', $form_state->getValue('clave'))
            ->set('terminal', $form_state->getValue('terminal'))
            ->set('moneda', $form_state->getValue('moneda'))
            ->set('trans', $form_state->getValue('trans'))
            ->save();
    }
}
