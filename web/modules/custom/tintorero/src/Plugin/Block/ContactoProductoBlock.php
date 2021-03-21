<?php

namespace Drupal\tintorero\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBuilderInterface;

/**
 * Provides a contacto producto block.
 *
 * @Block(
 *   id = "contacto_producto",
 *   admin_label = @Translation("Contacto Producto"),
 *   category = @Translation("hipertintorero")
 * )
 */
class ContactoProductoBlock extends BlockBase implements ContainerFactoryPluginInterface
{

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  protected $formBuilder;

  /**
   * Constructs a new ContactoProductoBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $current_user, FormBuilderInterface $form_builder)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('form_builder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state)
  {
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
      '#default_value' => $this->configuration['email'],
    ];
    return $form;
  }

  public function blockValidate($form, FormStateInterface $form_state)
  {
    if (!valid_email_address($form_state->getValue('email'))) {
      $form_state->setErrorByName(
        'contacto_producto_block_message',
        $this->t('Introduce una direccion de Email valida')
      );
    }
  }
  
  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state)
  {
    $this->configuration['email'] = $form_state->getValue('email');
  }

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $build = $this->formBuilder->getForm('Drupal\tintorero\Form\ContactoProductoForm');
    return $build;
  }
}
