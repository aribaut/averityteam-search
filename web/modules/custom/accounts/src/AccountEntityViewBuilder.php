<?php

namespace Drupal\accounts;

use Drupal\Core\Entity\EntityViewBuilder;

/**
 * Defines the view builder for my_entity_type entities.
 */
class AccountEntityViewBuilder extends EntityViewBuilder {
  /**
  $entity_type = 'node';
  $view_mode = 'teaser';

  $view_builder = \Drupal::entityTypeManager()->getViewBuilder($entity_type);
  $storage = \Drupal::entityTypeManager()->getStorage($entity_type);
  $node = $storage->load($nid);
  $build = $view_builder->view($node, $view_mode);
  $output = render($build);
   */
}