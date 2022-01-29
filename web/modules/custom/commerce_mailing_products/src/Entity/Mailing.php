<?php

namespace Drupal\commerce_mailing_products\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the mailing entity.
 *
 * @ContentEntityType(
 *   id = "mailing",
 *   label = @Translation("Mailing"),
 *   base_table = "commerce_mailings_products",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid",
 *     "published" = "status",
 *     "owner" = "uid",
 *     "uid" = "uid",
 *   },
 *   handlers = {
 *     "form" = {
 *       "default" = "Drupal\Core\Entity\ContentEntityForm",
 *       "add" = "Drupal\Core\Entity\ContentEntityForm",
 *       "edit" = "Drupal\Core\Entity\ContentEntityForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *   },
 *   links = {
 *     "canonical" = "/mailing/{mailing}",
 *     "add-page" = "/mailing/add",
 *     "add-form" = "/mailing/add/{advertiser_type}",
 *     "edit-form" = "/mailing/{advertiser}/edit",
 *     "delete-form" = "/mailing/{advertiser}/delete",
 *     "collection" = "/admin/content/mailings",
 *   },
 *   admin_permission = "administer site configuration",
 *   translatable = TRUE,
 * )
 */
class Mailing extends ContentEntityBase implements ContentEntityInterface
{

    public function getTitle()
    {
        return $this->get('title')->value;
    }

    public function setTitle($title)
    {
        $this->set('title', $title);
        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function getCreatedTime()
    {
        return $this->get('created')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedTime($timestamp)
    {
        $this->set('created', $timestamp);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedTime()
    {
        return $this->get('updated')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedTime($timestamp)
    {
        $this->set('updated', $timestamp);
        return $this;
    }

    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {

        // Standard field, used as unique if primary index.
        $fields['id'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('ID'))
            ->setDescription(t('The ID of the mailing entity.'))
            ->setReadOnly(TRUE);

        // Standard field, unique outside of the scope of the current project.
        $fields['uuid'] = BaseFieldDefinition::create('uuid')
            ->setLabel(t('UUID'))
            ->setDescription(t('The UUID of the Mailing entity.'))
            ->setReadOnly(TRUE);

        $fields['title'] = BaseFieldDefinition::create('string')
            ->setLabel('Title')
            ->setDescription('')
            ->setRequired(TRUE);

        $fields['langcode'] = BaseFieldDefinition::create('language')
            ->setLabel(t('Language'))
            ->setTranslatable(true)
            ->setDescription(t('The redirect language code.'))
            ->setDisplayOptions('form', array(
                'type' => 'language_select',
                //'weight' => 0,
            ));

        $fields['created'] = BaseFieldDefinition::create('created')
            ->setLabel('Creado')
            ->setDescription('Created mailing entity.');

        $fields['changed'] = BaseFieldDefinition::create('changed')
            ->setLabel('Modificado')
            ->setDescription('Updated Mailing entity.');
        return $fields;
    }


    /**
     * {@inheritdoc}
     */
    public function preSave(EntityStorageInterface $storage)
    {
        parent::preSave($storage);

        foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
            $translation = $this->getTranslation($langcode);

            // Explicitly set the owner ID to 0 if the translation owner is anonymous
            // (This will ensure we don't store a broken reference in case the user
            // no longer exists).
            if ($translation->getOwner()->isAnonymous()) {
                $translation->setOwnerId(0);
            }
        }
    }
}
