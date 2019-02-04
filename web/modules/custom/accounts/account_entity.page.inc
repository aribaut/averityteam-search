<?php

/**
 * @file
 * Contains account_entity.page.inc.
 *
 * Page callback for Account entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Account entity templates.
 *
 * Default template: account_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_account_entity(array &$variables) {
  // Fetch AccountEntity Entity Object.
  $account_entity = $variables['elements']['#account_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
