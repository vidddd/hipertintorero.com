<?php

namespace Drupal\secciones\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Seccion entity entities.
 */
interface SeccionEntityInterface extends ConfigEntityInterface
{

  /*
  * Returns the url of the section
  * @return string
  */
  public function getUrlSeccion();

  /*
  * Returns Name of section
  * @return string
  */
  public function getNombre();

  /*
  * Return de image of section
  * @return string
  */
  public function getImage();

  /*
   * Returns the color of the section
   * @return string
   */
  public function getColor();

  /*
   * Sets the url of the section
   * @params string @pattern
   * 
   * @return $this
   */
  public function setUrlSection($pattern);

  /*
   * Sets the name of the section
   * @params string @nombre
   * 
   * @return $this
   */
  public function setNombre($nombre);

  /*
   * Sets the image of the section
   * @params string @image
   * 
   * @return $this
   */
  public function setImage($image);

  /*
   * Sets the color of the section
   * @params string @color
   * 
   * @return $this
   */
  public function setColor($color);

}
