<?php

namespace Drupal\accounts;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface AccountEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Account entity revision IDs for a specific Account entity.
   *
   * @param \Drupal\accounts\Entity\AccountEntityInterface $entity
   *   The Account entity entity.
   *
   * @return int[]
   *   Account entity revision IDs (in ascending order).
   */
  public function revisionIds(AccountEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Account entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Account entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\accounts\Entity\AccountEntityInterface $entity
   *   The Account entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(AccountEntityInterface $entity);

  /**
   * Unsets the language for all Account entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
