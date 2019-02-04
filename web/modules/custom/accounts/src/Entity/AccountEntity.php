<?php

namespace Drupal\accounts\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Account entity entity.
 *
 * @ingroup accounts
 *
 * @ContentEntityType(
 *   id = "account_entity",
 *   label = @Translation("Account entity"),
 *   handlers = {
 *     "storage" = "Drupal\accounts\AccountEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\accounts\AccountEntityListBuilder",
 *     "views_data" = "Drupal\accounts\Entity\AccountEntityViewsData",
 *     "translation" = "Drupal\accounts\AccountEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\accounts\Form\AccountEntityForm",
 *       "add" = "Drupal\accounts\Form\AccountEntityForm",
 *       "edit" = "Drupal\accounts\Form\AccountEntityForm",
 *       "delete" = "Drupal\accounts\Form\AccountEntityDeleteForm",
 *     },
 *     "access" = "Drupal\accounts\AccountEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\accounts\AccountEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "account_entity",
 *   data_table = "account_entity_field_data",
 *   revision_table = "account_entity_revision",
 *   revision_data_table = "account_entity_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer account entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/account_entity/{account_entity}",
 *     "add-form" = "/admin/structure/account_entity/add",
 *     "edit-form" = "/admin/structure/account_entity/{account_entity}/edit",
 *     "delete-form" = "/admin/structure/account_entity/{account_entity}/delete",
 *     "version-history" = "/admin/structure/account_entity/{account_entity}/revisions",
 *     "revision" = "/admin/structure/account_entity/{account_entity}/revisions/{account_entity_revision}/view",
 *     "revision_revert" = "/admin/structure/account_entity/{account_entity}/revisions/{account_entity_revision}/revert",
 *     "revision_delete" = "/admin/structure/account_entity/{account_entity}/revisions/{account_entity_revision}/delete",
 *     "translation_revert" = "/admin/structure/account_entity/{account_entity}/revisions/{account_entity_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/account_entity",
 *   },
 *   field_ui_base_route = "account_entity.settings"
 * )
 */
class AccountEntity extends RevisionableContentEntityBase implements AccountEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the account_entity owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCrelateId() {
      return $this->get('crelate_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getSignedAgreement() {
      return $this->get('signed_agreement')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSignedAgreement($signed_agreement) {
      $this->set('signed_agreement', $signed_agreement ? TRUE : FALSE);
      return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getInterviewedLast() {
      return $this->get('interviewed_last')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setInterviewedLast($timestamp) {
      $this->set('interviewed_last', $timestamp);
      return $this;
  }

  /**
   * {@inheritdoc}
   */
  /*
  public function getAddress() {
      return $this->get('address')->value;
  }
*/

  /**
   * {@inheritdoc}
   */
  /*
  public function setAddress($address) {
      $this->set('address', $address);
      return $this;
  }
*/

  /**
   * {@inheritdoc}
   */
  public function getTechStack() {
      return $this->get('tech_stack')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTechStack($tech_stack) {
      $this->set('tech_stack', $tech_stack);
      return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getIndustry() {
      return $this->get('industry')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setIndustry($industry) {
      $this->set('industry', $industry);
      return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getURL() {
      return $this->get('url')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setURL($url) {
      $this->set('url', $url);
      return $this;
  }

    /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Account entity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the company.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 80,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Account entity is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    /**
     * Adding custom fields to the Account entity
     * @todo add getters above for all added custom fields below
     */

    /**
     * Crelate ID is a unique identifier for the Crelate database. It is also used to form url targeting the specific resource on crelate.com
     * e.g: https://app.crelate.com/go#stage/_Accounts/DefaultView/89d3b52a-40e9-47bc-9872-a78a012e0ff8
     * where the ID is the Crelate ID for that account (VICE).
     */
    $fields['crelate_id'] = BaseFieldDefinition::create('string')
        ->setLabel(t('Crelate ID'))
        ->setDescription(t('Unique identifier coming from crelate.com'))
        ->setRevisionable(FALSE)
        ->setTranslatable(FALSE)
        ->setDisplayOptions('form', array(
            'type' => 'string_textfield',
            'settings' => array(
            'display_label' => FALSE,
            ),
        ))
        ->setDisplayOptions('view', array(
            'label' => 'hidden',
            'type' => 'string',
        ))
        ->setDisplayConfigurable('form', FALSE)
        ->setRequired(TRUE)
        ->setReadOnly(TRUE);

      $fields['signed_agreement'] = BaseFieldDefinition::create('boolean')
          ->setLabel(t('Signed Agreement'))
          ->setDescription(t('A boolean indicating whether the Account has a signed agreement with Averity.'))
          ->setRevisionable(TRUE)
          ->setDefaultValue(TRUE)
          ->setDisplayOptions('form', [
              'type' => 'boolean_checkbox',
              'weight' => -3,
          ])
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);

      $fields['interviewed_last'] = BaseFieldDefinition::create('datetime')
          ->setLabel(t('Interviewed Last'))
          ->setDescription(t('The last recorded date we interviewed that account.'))
          ->setRevisionable(TRUE)
          ->setSettings([
              'datetime_type' => 'date'
          ])
          ->setDefaultValue('')
          ->setDisplayOptions('view', [
              'label' => 'above',
              'type' => 'datetime_default',
              'settings' => [
                  'format_type' => 'medium',
              ],
              'weight' => -4,
          ])
          ->setDisplayOptions('form', [
              'type' => 'datetime_default',
              'weight' => -4,
          ])
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);
/*
      $fields['address'] = BaseFieldDefinition::create('address')
          ->setLabel(t('Address'))
          ->setSettings(array(
              'default_value' => array(),
          ))
          ->setDisplayOptions('view', array(
              'label' => 'above',
              'type' => 'address_default',
          ))
          ->setDisplayOptions('form', array(
              'type' => 'address_default',
          ))
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);
*/

      $fields['url'] = BaseFieldDefinition::create('uri')
          ->setLabel(t('Crelate Link'))
          ->setDescription(t('The crelate.com link back to the account default display page.'))
          ->setSetting('max_length', 2048)
          ->setSetting('case_sensitive', TRUE)
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);

      $fields['tech_stack'] = BaseFieldDefinition::create('entity_reference')
          ->setLabel(t('Technology Stacks'))
          ->setDescription(t('Technology Stacks used at this company.'))
          ->setSetting('target_type', 'taxonomy_term')
          ->setSetting('handler', 'default:taxonomy_term')
          ->setSetting('handler_settings', ['target_bundles' => ['tech_stack' => 'tech_stack']])
          ->setDisplayOptions('view', [
              'label'  => 'hidden',
              'type'   => 'tech_stack', //unsure
              'weight' => 0,
          ])
          ->setDisplayOptions('form', array(
              'type'     => 'entity_reference_autocomplete',
              'weight'   => 5,
              'settings' => array(
                  'match_operator'    => 'CONTAINS',
                  'size'              => '60',
                  'autocomplete_type' => 'tech_stack', //unsure
                  'placeholder'       => '',
              ),
          ))
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);

      $fields['industry'] = BaseFieldDefinition::create('entity_reference')
          ->setLabel(t('Industry'))
          ->setDescription(t('Industry of the company.'))
          ->setSetting('target_type', 'taxonomy_term')
          ->setSetting('handler', 'default:taxonomy_term')
          ->setSetting('handler_settings', ['target_bundles' => ['industry' => 'industry']])
          ->setDisplayOptions('view', [
              'label'  => 'hidden',
              'type'   => 'industry', //unsure
              'weight' => 0,
          ])
          ->setDisplayOptions('form', array(
              'type'     => 'entity_reference_autocomplete',
              'weight'   => 5,
              'settings' => array(
                  'match_operator'    => 'CONTAINS',
                  'size'              => '60',
                  'autocomplete_type' => 'industry', //unsure
                  'placeholder'       => '',
              ),
          ))
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
