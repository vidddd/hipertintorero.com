<?php

namespace Drupal\secciones\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SeccionEntityForm.
 */
class SeccionEntityForm extends EntityForm
{

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state)
  {
    $form = parent::form($form, $form_state);

    $seccion_entity = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $seccion_entity->label(),
      '#description' => $this->t("Label for the Seccion entity."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $seccion_entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\secciones\Entity\SeccionEntity::load',
      ],
      '#disabled' => !$seccion_entity->isNew(),
    ];

    $form['urlSeccion'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Url Seccion'),
      '#default_value' => $seccion_entity->getUrlSeccion(),
      '#description' => $this->t('The url of the section'),
      '#maxlength' => 255,
      '#required' => TRUE
    ];

    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#default_value' => $seccion_entity->getNombre(),
      '#description' => $this->t('The name of the section'),
      '#maxlength' => 255
    ];

    $form['image'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Image'),
      '#default_value' => $seccion_entity->getImage(),
      '#description' => $this->t('The image of the section'),
      '#maxlength' => 255,
      '#required' => TRUE
    ];

    $form['color'] = [
      '#type' => 'color',
      '#title' => $this->t('Color Seccion'),
      '#default_value' => $seccion_entity->getColor(),
      '#description' => $this->t('The color of the section'),
      '#required' => TRUE
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state)
  {
    $seccion_entity = $this->entity;
    $status = $seccion_entity->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Seccion entity.', [
          '%label' => $seccion_entity->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Seccion entity.', [
          '%label' => $seccion_entity->label(),
        ]));
    }
    $form_state->setRedirectUrl($seccion_entity->toUrl('collection'));
  }
}
