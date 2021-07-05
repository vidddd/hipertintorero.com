<?php

/**
 * @file
 * Module file for the Commerce Redsys module.
 */

use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Implements hook_help().
 */
function commerce_redsys_payment_help($route_name, RouteMatchInterface $route_match)
{
  switch ($route_name) {
    case 'help.page.commerce_redsys_payment':
      $variables = [
        ':url' => 'https://pagosonline.redsys.es/descargas.html',
      ];

      $output = '';
      $output .= '<h3>' . t('About') . '</h3>';
      $output .= '<p>' . t('Redsys Payments Module for Drupal Commerce, <a href=":url">Redsys</a>.', $variables) . '</p>';

      return $output;
  }
}
