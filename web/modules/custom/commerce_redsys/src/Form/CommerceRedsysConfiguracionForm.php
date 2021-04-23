<?php

namespace Drupal\commerce_redsys\Form;

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
            'commerce_redsys.configuracion',
        ];
    }

    public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL)
    {
        $config = $this->config('commerce_redsys.configuracion');


        $form['configuracion'] = array(
            '#type'  => 'fieldset',
            '#title' => $this->t('Configuraciones de La Pasarela de Pago Commerce Redsys'),
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
        $form['configuracion']['terminal'] = array(
            '#type'          => 'textfield',
            '#title'         => 'Terminal',
            '#default_value' => $config->get('terminal'),
        );
        $form['configuracion']['moneda'] = array(
            '#type'          => 'textfield',
            '#title'         => 'Moneda',
            '#default_value' => $config->get('moneda'),
        );
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('commerce_redsys.configuracion')
            ->set('redirect_url', $form_state->getValue('redirect_url'))
            ->set('fuc', $form_state->getValue('fuc'))
            ->set('terminal', $form_state->getValue('terminal'))
            ->set('moneda', $form_state->getValue('moneda'))
            
            ->save();
    }
}
