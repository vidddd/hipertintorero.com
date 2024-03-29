<?php

namespace Drupal\tintorero_bulk_upload\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Builds the form to delete Media Bulk Config entities.
 */
class MediaBulkConfigDeleteForm extends EntityConfirmFormBase implements ContainerInjectionInterface
{

  /**
   * MediaBulkConfigDeleteForm constructor.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   */
  public function __construct(MessengerInterface $messenger)
  {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion()
  {
    return $this->t('Are you sure you want to delete %name?', ['%name' => $this->entity->label()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl()
  {
    return new Url('entity.tintorero_bulk_config.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText()
  {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $this->entity->delete();

    $this->messenger->addMessage(
      $this->t(
        'content @type: deleted @label.',
        [
          '@type' => $this->entity->bundle(),
          '@label' => $this->entity->label(),
        ]
      )
    );

    $form_state->setRedirectUrl($this->getCancelUrl());
  }
}
