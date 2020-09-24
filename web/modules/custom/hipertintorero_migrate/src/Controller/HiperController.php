<?php

namespace Drupal\hipertintorero_migrate\Controller;

use Drupal\Core\Controller\ControllerBase;

class HiperController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   */
  public function content() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello, World!'),
    ];
  }

}
