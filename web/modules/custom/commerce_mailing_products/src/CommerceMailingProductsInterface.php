<?php

namespace Drupal\commerce_mailing_products;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a commerce_mailing_products entity type.
 */
interface CommerceMailingProductsInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the commerce_mailing_products title.
   *
   * @return string
   *   Title of the commerce_mailing_products.
   */
  public function getTitle();

  /**
   * Sets the commerce_mailing_products title.
   *
   * @param string $title
   *   The commerce_mailing_products title.
   *
   * @return \Drupal\commerce_mailing_products\CommerceMailingProductsInterface
   *   The called commerce_mailing_products entity.
   */
  public function setTitle($title);

  /**
   * Gets the commerce_mailing_products creation timestamp.
   *
   * @return int
   *   Creation timestamp of the commerce_mailing_products.
   */
  public function getCreatedTime();

  /**
   * Sets the commerce_mailing_products creation timestamp.
   *
   * @param int $timestamp
   *   The commerce_mailing_products creation timestamp.
   *
   * @return \Drupal\commerce_mailing_products\CommerceMailingProductsInterface
   *   The called commerce_mailing_products entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the commerce_mailing_products status.
   *
   * @return bool
   *   TRUE if the commerce_mailing_products is enabled, FALSE otherwise.
   */
  public function isEnabled();

  /**
   * Sets the commerce_mailing_products status.
   *
   * @param bool $status
   *   TRUE to enable this commerce_mailing_products, FALSE to disable.
   *
   * @return \Drupal\commerce_mailing_products\CommerceMailingProductsInterface
   *   The called commerce_mailing_products entity.
   */
  public function setStatus($status);

}
