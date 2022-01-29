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
     * @return \Drupal\commerce_mailing_products\Entity\MailingInterface
     *   The called Mailing entity.
     */
    public function setTitle($title);
}
