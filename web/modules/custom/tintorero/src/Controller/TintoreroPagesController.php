<?php

namespace Drupal\tintorero\Controller;

use Drupal\Core\Controller\ControllerBase;

class TintoreroPagesController extends ControllerBase
{
    //...
    public function tab1()
    {
        return array(
            '#markup' => '<p>' . $this->t('This is the content of Tab 1') .
                '</p>',
        );
    }
    public function tab2()
    {
        return array(
            '#markup' => '<p>' . $this->t('This is the content of Tab 2') .
                '</p>',
        );
    }
    public function tab3()
    {
        return array(
            '#markup' => '<p>' . $this->t('This is the content of Tab 3') .
                '</p>',
        );
    }
}
