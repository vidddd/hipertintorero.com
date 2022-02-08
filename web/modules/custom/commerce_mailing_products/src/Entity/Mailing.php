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
 *   label_collection = @Translation("Mailings"),
 *   label_singular = @Translation("mailing"),
 *   label_plural = @Translation("mailings"),
 *   label_count = @PluralTranslation(
 *     singular = "@count mailing",
 *     plural = "@count mailings",
 *   ),
 *   base_table = "commerce_mailing_products",
 *   fieldable = TRUE,
 *   bundle_label = @Translation("Mailing type"),
 *   bundle_entity_type = "mailing_type",
 *   field_ui_base_route = "entity.mailing_type.edit_form",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "langcode" = "langcode",
 *     "bundle" = "mailing_type",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *     "uid" = "uid",
 *   },
 *   handlers = {
 *     "view_builder" = "Drupal\commerce_mailing_products\View\MailingViewBuilder",
 *     "list_builder" = "Drupal\commerce_mailing_products\Config\Entity\MailingListBuilder",
 *     "views_data" = "Drupal\entity\EntityViewsData",
 *     "storage" = "Drupal\commerce_mailing_products\Storage\MailingStorage",
 *     "form" = {
 *       "default" = "Drupal\commerce_mailing_products\Form\MailingForm",
 *       "add" = "Drupal\commerce_mailing_products\Form\MailingForm",
 *       "edit" = "Drupal\commerce_mailing_products\Form\MailingForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider"
 *     },
 *   },
 *   links = {
 *     "canonical" = "/admin/commerce/mailing-products/mailing/{mailing}",
 *     "add-page" = "/admin/commerce/mailing-products/mailing/add",
 *     "add-form" = "/admin/commerce/mailing-products/mailing/add/{mailing_type}",
 *     "edit-form" = "/admin/commerce/mailing-products/mailing/{mailing}/edit",
 *     "delete-form" = "/admin/commerce/mailing-products/mailing/{mailing}/delete",
 *     "collection" = "/admin/commerce/mailing-products",
 *   },
 *   admin_permission = "administer mailing products",
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
     * Gets the value for the advertiser_body field of an advertiser entity.
     */
    public function getBody()
    {
        return $this->get('body')->value;
    }

    /**
     * Sets the value for the body field of an advertiser entity.
     */
    public function setBody($body)
    {
        $this->get('body')->value = $body;
        return $this;
    }

    public function getStatus()
    {
        return $this->get('status')->value;
    }

    public function setStatus($status)
    {
        $this->set('status', $status);
        return $this;
    }

    public function getCreatedTime()
    {
        return $this->get('created')->value;
    }

    public function setCreatedTime($timestamp)
    {
        $this->set('created', $timestamp);
        return $this;
    }

    public function getUpdatedTime()
    {
        return $this->get('updated')->value;
    }

    public function setUpdatedTime($timestamp)
    {
        $this->set('updated', $timestamp);
        return $this;
    }

    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {
        $fields = parent::baseFieldDefinitions($entity_type);

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
            ->setLabel(t('Title'))
            ->setRequired(TRUE)
            ->setDisplayConfigurable('form', TRUE)
            ->setRevisionable(TRUE)
            ->setSetting('max_length', 255)
            ->setDisplayOptions('view', [
                'label' => 'hidden',
                'type' => 'string',
                'weight' => -5,
            ])
            ->setDisplayOptions('form', [
                'type' => 'string_textfield',
                'weight' => -5,
            ]);


        $fields['body'] = BaseFieldDefinition::create('text_long')
            ->setLabel(t('Description'))
            ->setSetting('weight', 0)
            ->setRequired(TRUE)
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE)
            ->setDisplayOptions('form', [
                'type' => 'text_textarea',
            ]);

        $fields['products'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel(t('Products'))
            ->setDescription(t('The  Mailing products.'))
            ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
            ->setSetting('target_type', 'commerce_product')
            ->setSetting('handler', 'default')
            ->setDisplayOptions('form', [
                'type' => 'commerce_entity_select',
                'weight' => -10,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['langcode'] = BaseFieldDefinition::create('language')
            ->setLabel(t('Language'))
            ->setTranslatable(true)
            ->setDescription(t('The redirect language code.'))
            ->setDisplayOptions('form', array(
                'type' => 'language_select',
                //'weight' => 0,
            ));

        $fields['status'] = BaseFieldDefinition::create('boolean')
            ->setLabel(t('Status'))
            ->setDisplayOptions('form', [
                'type' => 'boolean_checkbox',
                'settings' => [
                    'display_label' => TRUE,
                ],
                'weight' => 90,
            ])
            ->setDisplayConfigurable('form', TRUE);

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
        /*
        foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
            $translation = $this->getTranslation($langcode);

            // Explicitly set the owner ID to 0 if the translation owner is anonymous
            // (This will ensure we don't store a broken reference in case the user
            // no longer exists).
            if ($translation->getOwner()->isAnonymous()) {
                $translation->setOwnerId(0);
            }
        }
        */
    }
}
