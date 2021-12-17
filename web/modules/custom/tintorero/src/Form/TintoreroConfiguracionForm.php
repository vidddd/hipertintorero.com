<?php

namespace Drupal\tintorero\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */

class TintoreroConfiguracionForm extends ConfigFormBase
{

  public function getFormId()
  {
    return 'tintorero_configuracion';
  }

  protected function getEditableConfigNames()
  {
    return [
      'tintorero.configuracion',
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL)
  {
    $config = $this->config('tintorero.configuracion');


    $form['configuracion'] = array(
      '#type'  => 'fieldset',
      '#title' => $this->t('Configuraciones'),
    );
    $form['configuracion']['email_notify'] = array(
      '#type'          => 'textfield',
      '#title'         => 'Email Notificaciones de Error',
      '#default_value' => $config->get('email_notify'),
    );
    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $this->config('tintorero.configuracion')
      ->set('email_notify', $form_state->getValue('email_notify'))
      ->save();
  }
}
