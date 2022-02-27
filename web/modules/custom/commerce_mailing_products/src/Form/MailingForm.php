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

    $form['description'] = [
      '#title' => $this->t('Description'),
      '#type' => 'textarea',
      '#default_value' => '',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state)
  {
    $mailing = $this->entity;
    $insert = $mailing->isNew();
    $args = ['@type' => $mailing->label()];

    if ($insert) {
      $this->messenger()->addStatus($this->t('@type has been created.', $args));
    } else {
      $this->messenger()->addStatus($this->t('@type has been updated.', $args));
    }

    parent::save($form, $form_state);
    $form_state->setRedirect('entity.mailing.collection');
  }
}
