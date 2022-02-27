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

        $form['send_options'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Send Options'),
            '#description' => $this->t('Specify Send options for this Mailing Type'),
        ];
        $form['send_options']['send_type'] = [
            '#type' => 'select',
            '#title' => $this->t('Send Type'),
            '#default_value' => $mailing_type->getSendType(),
            '#options' => cmp_get_send_types(),
        ];
        $form['send_options']['format'] = [
            '#type' => 'radios',
            '#title' => $this->t('Format'),
            '#default_value' => $mailing_type->format,
            '#options' => cmp_format_options(),
        ];
        $form['send_options']['priority'] = [
            '#type' => 'select',
            '#title' => $this->t('Priority'),
            '#default_value' => $mailing_type->priority,
            '#options' => cmp_get_priority(),
        ];
        $form['compose_options'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Compose Options'),
            '#description' => $this->t('Specify Compose options for this Mailing Type'),
        ];

        /** @var \Drupal\Core\Entity\EntityFieldManager $field_manager */
        $field_manager = \Drupal::service('entity_field.manager');
        /*$instances = $field_manager->getFieldDefinitions('crm_core_individual', $type->id());
            foreach ($instances as $instance) {
                $options[$instance->getName()] = $instance->getLabel();
            }*/
        /*
        foreach ($this->defaultPrimaryFields as $primary_field) {
            $form['primary_fields_container'][$primary_field] = [
                '#type' => 'select',
                '#title' => $this->t('Primary @field field', ['@field' => $primary_field]),
                '#default_value' => empty($type->primary_fields[$primary_field]) ? '' : $type->primary_fields[$primary_field],
                '#empty_value' => '',
                '#empty_option' => $this->t('--Please Select--'),
                '#options' => $options,
            ];
        }*/


        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $form, FormStateInterface $form_state)
    {
        $mailing_type = $this->entity;
        $insert = $mailing_type->isNew();
        $args = ['@type' => $mailing_type->label()];

        if ($insert) {
            $this->messenger()->addStatus($this->t('@type has been created.', $args));
        } else {
            $this->messenger()->addStatus($this->t('@type has been updated.', $args));
        }
        parent::save($form, $form_state);
        $form_state->setRedirect('entity.mailing_type.collection');
    }
}
