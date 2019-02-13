<?php

namespace Drupal\accounts\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Account entity entities.
 *
 * @ingroup accounts
 */
interface AccountEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Account entity name.
   *
   * @return string
   *   Name of the Account entity.
   */
  public function getName();

  /**
   * Sets the Account entity name.
   *
   * @param string $name
   *   The Account entity name.
   *
   * @return \Drupal\accounts\Entity\AccountEntityInterface
   *   The called Account entity entity.
   */
  public function setName($name);

  /**
   * Gets the Account entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Account entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Account entity creation timestamp.
   *
   * @param int $timestamp
   *   The Account entity creation timestamp.
   *
   * @return \Drupal\accounts\Entity\AccountEntityInterface
   *   The called Account entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Account entity published status indicator.
   *
   * Unpublished Account entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Account entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Account entity.
   *
   * @param bool $published
   *   TRUE to set this Account entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\accounts\Entity\AccountEntityInterface
   *   The called Account entity entity.
   */
  public function setPublished($published);

  /**
   * Gets the Account entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Account entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\accounts\Entity\AccountEntityInterface
   *   The called Account entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Account entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Account entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\accounts\Entity\AccountEntityInterface
   *   The called Account entity entity.
   */
  public function setRevisionUserId($uid);

  /**
   * Custom getters and setters for the Account interface.
   */

  /**
   * Gets the crelate ID of the Account entity.
   *
   * @return string
   *   ID string of the Account entity.
   */
  public function getCrelateId();

  /**
   * Gets the Signed Agreement status of the Account entity.
   *
   * @return boolean
   *   The status of the Signed Agreement for the Account entity.
   */
  public function getSignedAgreement();

  /**
   * Sets the Signed Agreement of the Account entity.
   *
   * @param bool
   * The Account entity signed agreement flag.
   *
   * @return bool
   * The status flag of the entity signed agreement.
   */
  public function setSignedAgreement($signed_agreement);

  /**
   * Gets the last interviewed timestamp of the Account entity.
   *
   * @return int
   *   The UNIX timestamp of when this account was last interviewed.
   */
  public function getInterviewedLast();

  /**
   * Sets the last interviewed timestamp of the Account entity.
   *
   * @param int $timestamp
   * The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\address\AddressInterface
   *   The called Account entity entity.
   */
  public function setInterviewedLast($timestamp);

  /**
   * Gets the address of the Account entity.
   *
   * @todo find out the data structure for this field...
   * @return \Drupal\address\AddressInterface
   *   The address field values of the account entity.
   */
  public function getAddress();

  /**
   * Sets the address of the Account entity.
   *
   * @param int $address
   * The address field values for the Account entity.
   *
   * @return \Drupal\accounts\Entity\AccountEntityInterface
   *   The called Account entity entity.
   */
  public function setAddress($address);

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
