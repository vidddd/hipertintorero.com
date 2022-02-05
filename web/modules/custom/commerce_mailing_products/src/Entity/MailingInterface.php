<?php

namespace Drupal\commerce_mailing_products\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Represents a Sorteo entity.
 */
interface MailingInterface extends ContentEntityInterface, EntityChangedInterface
{

    /**
     * Gets the Sorteo title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Sets the Sorteo title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title);

    /**
     * Gets the product creation timestamp.
     *
     * @return int
     *   The product creation timestamp.
     */
    public function getCreatedTime();

    /**
     * Sets the product creation timestamp.
     *
     * @param int $timestamp
     *   The product creation timestamp.
     *
     * @return $this
     */
    public function setCreatedTime($timestamp);
}
