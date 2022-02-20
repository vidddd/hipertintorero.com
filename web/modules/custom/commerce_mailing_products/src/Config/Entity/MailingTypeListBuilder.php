<?php

namespace Drupal\commerce_mailing_products\Config\Entity;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Defines the list builder for mailing types.
 */
class MailingTypeListBuilder extends ConfigEntityListBuilder
{

    public function render()
    {
        $build = parent::render();
        return $build;
    }

    /**
     * {@inheritdoc}
     */
    public function buildHeader()
    {
        $header['type'] = $this->t('Mailing type');
        $header['label'] = $this->t('Label');
        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity)
    {
        $row['type'] = $entity->toLink(NULL, 'edit-form');
        $row['label'] = $entity->getLabel2();
        return $row + parent::buildRow($entity);
    }
}
