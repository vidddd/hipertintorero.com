<?php

namespace Drupal\secciones;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Seccion entity entities.
 */
class SeccionEntityListBuilder extends ConfigEntityListBuilder
{

  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['label'] = $this->t('Seccion');
    $header['id'] = $this->t('Machine name');
    $header['urlSeccion'] = $this->t('Url Seccion');
    $header['image'] = $this->t('Image');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    $row['urlSeccion'] = $entity->getUrlSeccion();
    $row['image'] = $entity->getImage();

    return $row + parent::buildRow($entity);
  }
}
