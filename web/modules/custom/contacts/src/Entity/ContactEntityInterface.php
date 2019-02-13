<?php

namespace Drupal\contacts\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Contact entity entities.
 *
 * @ingroup contacts
 */
interface ContactEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Contact entity name.
   *
   * @return string
   *   Name of the Contact entity.
   */
  public function getName();

  /**
   * Sets the Contact entity name.
   *
   * @param string $name
   *   The Contact entity name.
   *
   * @return \Drupal\contacts\Entity\ContactEntityInterface
   *   The called Contact entity entity.
   */
  public function setName($name);

  /**
   * Gets the Contact entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Contact entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Contact entity creation timestamp.
   *
   * @param int $timestamp
   *   The Contact entity creation timestamp.
   *
   * @return \Drupal\contacts\Entity\ContactEntityInterface
   *   The called Contact entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Contact entity published status indicator.
   *
   * Unpublished Contact entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Contact entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Contact entity.
   *
   * @param bool $published
   *   TRUE to set this Contact entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\contacts\Entity\ContactEntityInterface
   *   The called Contact entity entity.
   */
  public function setPublished($published);

  /**
   * Gets the Contact entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Contact entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\contacts\Entity\ContactEntityInterface
   *   The called Contact entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Contact entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Contact entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\contacts\Entity\ContactEntityInterface
   *   The called Contact entity entity.
   */
  public function setRevisionUserId($uid);

  /**
   * Custom getters and setters for the Contact interface.
   */

  /**
   * Gets the crelate ID of the Account entity.
   *
   * @return string
   *   ID string of the Account entity.
   */
  public function getCrelateId();

  /**
   * Gets the Contact entity name.
   *
   * @return string
   *   Name of the Contact entity.
   */
  public function getFirstName();

  /**
   * Sets the Contact entity name.
   *
   * @param string $name
   *   The Contact entity name.
   *
   * @return \Drupal\contacts\Entity\ContactEntityInterface
   *   The called Contact entity entity.
   */
  public function setFirstName($name);

  /**
   * Gets the Contact entity name.
   *
   * @return string
   *   Name of the Contact entity.
   */
  public function getLastName();

  /**
   * Sets the Contact entity name.
   *
   * @param string $name
   *   The Contact entity name.
   *
   * @return \Drupal\contacts\Entity\ContactEntityInterface
   *   The called Contact entity entity.
   */
  public function setLastName($name);

  /**
   * Gets the Signed Agreement status of the Account entity.
   *
   * @return boolean
   *   The status of the Signed Agreement for the Account entity.
   */
  public function getHotCandidate();

  /**
   * Sets the Signed Agreement of the Account entity.
   *
   * @param bool
   * The Account entity signed agreement flag.
   *
   * @return bool
   * The status flag of the entity signed agreement.
   */
  public function setHotCandidate($hot_candidate);

  /**
   * Gets the tech stack of the Account entity.
   *
   * @return int
   * The vocabulary ID of the taxonomy term.
   */
  public function getTechStack();

  /**
   * Sets the tech stack of the Account entity.
   *
   * @param int $tech_stack
   * The vocabulary ID of the taxonomy term.
   *
   * @return \Drupal\accounts\Entity\AccountEntityInterface
   *   The called Account entity entity.
   */
  public function setTechStack($tech_stack);

  /**
   * Gets the tech stack of the Account entity.
   *
   * @return int
   * The vocabulary ID of the taxonomy term.
   */
  public function getIndustry();

  /**
   * Sets the tech stack of the Account entity.
   *
   * @param int $industry
   * The vocabulary ID of the taxonomy term.
   *
   * @return \Drupal\accounts\Entity\AccountEntityInterface
   *   The called Account entity entity.
   */
  public function setIndustry($industry);

  /**
   * Gets the crelate.com URL of the Account entity.
   *
   * @return string
   *   URL string of the Account entity.
   */
  public function getURL();

  /**
   * Sets the crelate.com URL of the Account entity.
   *
   * @param string $url
   * The Account entity url.
   *
   * @return \Drupal\accounts\Entity\AccountEntityInterface
   *   The called Account entity entity.
   */
  public function setURL($url);

}
