<?php

namespace Drupal\commerce_mailing_products\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Commerce Mailing Products settings.
 */
class CommerceMailingProductsSettingsForm extends ConfigFormBase
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $config = $this->config('commerce_mailing_products.settings');

        $form['cmp_sender_info'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Sender information'),
            '#collapsible' => FALSE,
            '#description' => $this->t("Default sender address that will only be used for confirmation emails. You can specify sender information for each newsletter separately on the newsletter's settings page."),
        ];
        $form['cmp_sender_info']['cmp_from_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('From name'),
            '#size' => 60,
            '#maxlength' => 128,
            '#default_value' => $config->get('newsletter.from_name'),
        ];
        $form['cmp_sender_info']['cmp_from_address'] = [
            '#type' => 'email',
            '#title' => $this->t('From email address'),
            '#size' => 60,
            '#maxlength' => 128,
            '#required' => TRUE,
            '#default_value' => $config->get('newsletter.from_address'),
        ];

        $form['cmp_default_options'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Default newsletter options'),
            '#collapsible' => FALSE,
            '#description' => $this->t('These options will be the defaults for new newsletters, but can be overridden in the newsletter editing form.'),
        ];
        $links = [':swiftmailer_url' => 'http://drupal.org/project/swiftmailer'];
        $description = $this->t('Default newsletter format. Install <a href=":swiftmailer_url">Swift Mailer</a> module to send newsletters in HTML format.', $links);
        $form['cmp_default_options']['cmp_format'] = [
            '#type' => 'select',
            '#title' => $this->t('Format'),
            '#options' => cmp_format_options(),
            '#description' => $description,
            '#default_value' => $config->get('newsletter.format'),
        ];
        // @todo Do we need these master defaults for 'priority' and 'receipt'?
        $form['cmp_default_options']['cmp_priority'] = [
            '#type' => 'select',
            '#title' => $this->t('Priority'),
            '#options' => cmp_get_priority(),
            '#description' => $this->t('Note that email priority is ignored by a lot of email programs.'),
            '#default_value' => $config->get('newsletter.priority'),
        ];
        $form['cmp_default_options']['cmp_receipt'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Request receipt'),
            '#default_value' => $config->get('newsletter.receipt'),
            '#description' => $this->t('Request a Read Receipt from your newsletters. A lot of email programs ignore these so it is not a definitive indication of how many people have read your newsletter.'),
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('commerce_mailing_products.settings')
            ->set('newsletter.format', $form_state->getValue('cmp_format'))
            ->set('newsletter.priority', $form_state->getValue('cmp_priority'))
            ->set('newsletter.receipt', $form_state->getValue('cmp_receipt'))
            ->set('newsletter.from_name', $form_state->getValue('cmp_from_name'))
            ->set('newsletter.from_address', $form_state->getValue('cmp_from_address'))
            ->save();

        parent::submitForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'commerce_mailing_products_settings';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return ['commerce_mailing_products.settings'];
    }
}
