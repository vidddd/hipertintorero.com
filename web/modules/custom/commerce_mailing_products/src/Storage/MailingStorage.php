<?php

namespace Drupal\commerce_mailing_products\Storage;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

class MailingStorage extends SqlContentEntityStorage
{

    function doPreSave(EntityInterface $entity)
    {

        return parent::doPreSave($entity);
    }

    protected function doPostSave(EntityInterface $entity, $update)
    {

        return parent::doPostSave($entity, $update);
    }
}
