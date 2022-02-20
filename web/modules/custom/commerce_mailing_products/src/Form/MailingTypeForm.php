<?php

namespace Drupal\commerce_mailing_products\Form;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Mailing type authoring form.
 */
class MailingTypeForm extends BundleEntityFormBase
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

        $form['description'] = [
            '#title' => $this->t('Description'),
            '#type' => 'textarea',
            '#default_value' => $mailing_type->get('description'),
        ];

        $form['label2'] = [
            '#title' => $this->t('Label2'),
            '#type' => 'textfield',
            '#default_value' => $mailing_type->getlabel2(),
            '#description' => $this->t('The Label 2.'),
            '#required' => TRUE,
        ];
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $form, FormStateInterface $form_state)
    {
        parent::save($form, $form_state);
        $form_state->setRedirect('entity.mailing_type.collection');
    }
}
