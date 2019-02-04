<?php

namespace Drupal\accounts;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Account entity entity.
 *
 * @see \Drupal\accounts\Entity\AccountEntity.
 */
class AccountEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\accounts\Entity\AccountEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished account entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published account entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit account entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete account entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add account entity entities');
  }

}
