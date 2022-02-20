<?php

namespace Drupal\commerce_mailing_products\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the mailing entity edit forms.
 */
class MailingForm extends ContentEntityForm
{
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state)
  {
    parent::save($form, $form_state);
    $form_state->setRedirect('entity.mailing.collection');
  }
}
