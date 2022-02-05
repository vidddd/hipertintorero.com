<?php

namespace Drupal\commerce_mailing_products\Form;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Mailing type authoring form.
 */
class MailingTypeEntityForm extends BundleEntityFormBase
{

    /**
     * {@inheritdoc}
     */
    public function form(array $form, FormStateInterface $form_state)
    {
        $form = parent::form($form, $form_state);
        $mailing_type = $this->entity;

        $form['label'] = [
            '#title' => $this->t('Label'),
            '#type' => 'textfield',
            '#default_value' => $mailing_type->label(),
            '#description' => $this->t('The admin-facing name.'),
            '#required' => TRUE,
        ];

        $form['id'] = [
            '#type' => 'machine_name',
            '#default_value' => $mailing_type->id(),
            '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
            '#machine_name' => [
                'exists' => '\Drupal\commerce_mailing_products\Entity\MailingType::load',
                'source' => ['label'],
            ],
        ];

        return $form;
    }
}
