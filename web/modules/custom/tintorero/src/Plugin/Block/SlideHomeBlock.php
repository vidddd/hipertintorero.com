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
 *   id = "slidehome_block",
 *   admin_label = @Translation("Slide Home Block"),
 * )
 */
class SlideHomeBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {

    return [ '#markup' => '',];
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['slidehome_block_settings'] = $form_state->getValue('slidehome_block_settings');
  }
}
