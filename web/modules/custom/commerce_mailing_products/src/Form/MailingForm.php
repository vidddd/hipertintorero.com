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
  public function save(array $form, FormStateInterface $form_state)
  {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New commerce_mailing_products %label has been created.', $message_arguments));
      $this->logger('commerce_mailing_products')->notice('Created new commerce_mailing_products %label', $logger_arguments);
    } else {
      $this->messenger()->addStatus($this->t('The commerce_mailing_products %label has been updated.', $message_arguments));
      $this->logger('commerce_mailing_products')->notice('Updated new commerce_mailing_products %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.commerce_mailing_products.canonical', ['commerce_mailing_products' => $entity->id()]);
  }
}
