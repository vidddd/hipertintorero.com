<?php

namespace Drupal\commerce_mailing_products\View;

use Drupal\Core\Entity\EntityViewBuilder;


class MailingViewBuilder extends EntityViewBuilder
{

    public function buildComponents(array &$build, array $entities, array $displays, $view_mode)
    {
        parent::buildComponents($build, $entities, $displays, $view_mode);
    }
}
