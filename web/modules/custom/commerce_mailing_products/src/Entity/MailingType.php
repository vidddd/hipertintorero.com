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
 *   },
 *   handlers = {
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *     "list_builder" = "Drupal\commerce_mailing_products\Config\Entity\MailingTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\commerce_mailing_products\Form\MailingTypeEntityForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *   },
 *   links = {
 *     "add-form" = "/admin/commerce/mailing-products/mailing-types/add",
 *     "edit-form" = "/admin/commerce/mailing-products/mailing/mailing-types/manage/{mailing_type}",
 *     "delete-form" = "/admin/commerce/mailing-products/mailing-types/manage/{mailing_type}/delete",
 *     "collection" = "/admin/commerce/mailing/mailing/mailing-types"
 *   }
 * )
 */
class MailingType extends ConfigEntityBundleBase
{
}
