<?php

namespace Drupal\tintorero_bulk_upload\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Media Bulk Config entity.
 *
 * @ConfigEntityType(
 *   id = "tintorero_bulk_config",
 *   label = @Translation("Media Bulk Config"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\tintorero_bulk_upload\MediaBulkConfigListBuilder",
 *     "form" = {
 *       "add" = "Drupal\tintorero_bulk_upload\Form\MediaBulkConfigForm",
 *       "edit" = "Drupal\tintorero_bulk_upload\Form\MediaBulkConfigForm",
 *       "delete" = "Drupal\tintorero_bulk_upload\Form\MediaBulkConfigDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\tintorero_bulk_upload\MediaBulkConfigHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "tintorero_bulk_config",
 *   admin_permission = "administer tintorero_bulk_upload configuration",
 *   permission_granularity = "bundle",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *     "media_types",
 *     "show_alt",
 *     "show_title",
 *     "form_mode",
 *   },
 *   links = {
 *     "canonical" = "/admin/config/media/tintorero-bulk-config/{tintorero_bulk_config}",
 *     "add-form" = "/admin/config/media/tintorero-bulk-config/add",
 *     "edit-form" = "/admin/config/media/tintorero-bulk-config/{tintorero_bulk_config}/edit",
 *     "delete-form" = "/admin/config/media/tintorero-bulk-config/{tintorero_bulk_config}/delete",
 *     "collection" = "/admin/config/media/tintorero-bulk-config"
 *   }
 * )
 */
class MediaBulkConfig extends ConfigEntityBase implements MediaBulkConfigInterface
{

    /**
     * The Tintorero Bulk Config ID.
     *
     * @var string
     */
    protected $id;

    /**
     * The Tintorero Bulk Config label.
     *
     * @var string
     */
    protected $label;
}
