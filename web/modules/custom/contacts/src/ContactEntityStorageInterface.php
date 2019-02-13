<?php

namespace Drupal\contacts;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\contacts\Entity\ContactEntityInterface;

/**
 * Defines the storage handler class for Contact entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Contact entity entities.
 *
 * @ingroup contacts
 */
interface ContactEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Contact entity revision IDs for a specific Contact entity.
   *
   * @param \Drupal\contacts\Entity\ContactEntityInterface $entity
   *   The Contact entity entity.
   *
   * @return int[]
   *   Contact entity revision IDs (in ascending order).
   */
  public function revisionIds(ContactEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Contact entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Contact entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\contacts\Entity\ContactEntityInterface $entity
   *   The Contact entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(ContactEntityInterface $entity);

  /**
   * Unsets the language for all Contact entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
