<?php

namespace Drupal\accounts;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\accounts\Entity\AccountEntityInterface;

/**
 * Defines the storage handler class for Account entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Account entity entities.
 *
 * @ingroup accounts
 */
class AccountEntityStorage extends SqlContentEntityStorage implements AccountEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(AccountEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {account_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {account_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(AccountEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {account_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('account_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
