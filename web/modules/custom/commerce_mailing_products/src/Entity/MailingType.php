<?php

namespace Drupal\commerce_mailing_products\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the mailing type entity class.
 *
 * @ConfigEntityType(
 *   id = "mailing_type",
 *   label = @Translation("Mailing type"),
 *   label_collection = @Translation("Mailing types"),
 *   label_singular = @Translation("mailing type"),
 *   label_plural = @Translation("mailing types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count mailing type",
 *     plural = "@count mailing types",
 *   ),
 *   admin_permission = "administer mailing products",
 *   config_prefix = "type",
 *   bundle_of = "mailing",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "send_type",
 *     "format",
 *     "priority",
 *     "categories",
 *   },
 *   handlers = {
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *     "list_builder" = "Drupal\commerce_mailing_products\Config\Entity\MailingTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\commerce_mailing_products\Form\MailingTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *   },
 *   links = {
 *     "add-form" = "/admin/commerce/mailing-products/mailing-types/add",
 *     "edit-form" = "/admin/commerce/mailing-products/mailing/mailing-types/manage/{mailing_type}",
 *     "delete-form" = "/admin/commerce/mailing-products/mailing-types/manage/{mailing_type}/delete",
 *     "collection" = "/admin/commerce/mailing-products/mailing-types"
 *   }
 * )
 */

class MailingType extends ConfigEntityBundleBase
{
    /**
     * The machine-readable name of this type.
     *
     * @var string
     */
    protected $id;

    /**
     * The human-readable name of this type.
     *
     * @var string
     */
    protected $label;

    /**
     * A brief description of this type.
     *
     * @var string
     */
    protected $description;

    /**
     * HTML or plaintext newsletter indicator.
     *
     * @var string
     */
    public $format;

    /**
     * Priority indicator.
     *
     * @var int
     */
    public $priority;

    /**
     * Punctutal, diarily, weekly, monthly
     *
     * @var string
     */
    protected $send_type;

    /**
     * Categories
     *
     * An array of key-value pairs, where key is the primary field type and value
     * is real field name used for this type.
     *
     * @var array
     */
    protected $categories;

    //protected $roles;

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setSendType($send_type)
    {
        $this->send_type = $send_type;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function getSendType()
    {
        return $this->send_type;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    /**
     * Get Categories
     *
     * @return array Categories.
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
