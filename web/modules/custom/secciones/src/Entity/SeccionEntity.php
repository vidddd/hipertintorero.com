<?php

namespace Drupal\secciones\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Seccion entity entity.
 *
 * @ConfigEntityType(
 *   id = "seccion_entity",
 *   label = @Translation("Seccion entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\secciones\SeccionEntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\secciones\Form\SeccionEntityForm",
 *       "edit" = "Drupal\secciones\Form\SeccionEntityForm",
 *       "delete" = "Drupal\secciones\Form\SeccionEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\secciones\SeccionEntityHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "seccion_entity",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/tintorero/seccion_entity/{seccion_entity}",
 *     "add-form" = "/admin/tintorero/seccion_entity/add",
 *     "edit-form" = "/admin/tintorero/seccion_entity/{seccion_entity}/edit",
 *     "delete-form" = "/admin/tintorero/seccion_entity/{seccion_entity}/delete",
 *     "collection" = "/admin/tintorero/seccion_entity"
 *   }
 * )
 */
class SeccionEntity extends ConfigEntityBase implements SeccionEntityInterface
{

  /**
   * The Seccion entity ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Seccion entity label.
   *
   * @var string
   */
  protected $label;

  /*
   * The url of section
   * @var string
   */
  protected $urlSeccion;

  /*
  * The name of section
  * @var string
  */
  protected $nombre;

  /*
  * The image of section
  * @var string
  */
  protected $image;

  /* The color of the section
  * @var string
  */
  protected $color;

  /*
  * (@inheritdoc)
  */
  public function getUrlSeccion(){
    return $this->urlSeccion;
  }

  /*
  * (@inheritdoc)
  */
  public function getNombre(){
    return $this->nombre;
  }

  /*
  * (@inheritdoc)
  */
  public function getImage(){
    return $this->image;
  }

  /*
  * (@inheritdoc)
  */
  public function getColor(){
    return $this->color;
  }

  /*
  * (@inheritdoc)
  */
  public function setUrlSection($pattern){
    $this->urlSeccion = $pattern;
    return $this;
  }

  /*
  * (@inheritdoc)
  */
  public function setNombre($nombre){
    $this->nombre = $nombre;
    return $this;
  }

  /*
  * (@inheritdoc)
  */
  public function setImage($image){
    $this->image = $image;
    return $this;
  }

  /*
  * (@inheritdoc)
  */
  public function setColor($color){
    $this->color = $color;
    return $this;
  }

}
