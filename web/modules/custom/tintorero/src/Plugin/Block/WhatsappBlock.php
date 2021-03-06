<?php

namespace Drupal\tintorero\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "whatsapp_block",
 *   admin_label = @Translation("Whatsapp Block"),
 * )
 */
class WhatsappBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {

        return ['#markup' => '9999999999999999999999',];
    }

    /**
     * {@inheritdoc}
     */
    protected function blockAccess(AccountInterface $account)
    {
        return AccessResult::allowedIfHasPermission($account, 'access content');
    }

    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state)
    {
        $config = $this->getConfiguration();
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state)
    {
        $this->configuration['whatsapp_block_settings'] = $form_state->getValue('whatsapp_block_settings');
    }
}
