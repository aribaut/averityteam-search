<?php

namespace Drupal\contacts\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Contact entity entity.
 *
 * @ingroup contacts
 *
 * @ContentEntityType(
 *   id = "contact_entity",
 *   label = @Translation("Contact entity"),
 *   handlers = {
 *     "storage" = "Drupal\contacts\ContactEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\contacts\ContactEntityListBuilder",
 *     "views_data" = "Drupal\contacts\Entity\ContactEntityViewsData",
 *     "translation" = "Drupal\contacts\ContactEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\contacts\Form\ContactEntityForm",
 *       "add" = "Drupal\contacts\Form\ContactEntityForm",
 *       "edit" = "Drupal\contacts\Form\ContactEntityForm",
 *       "delete" = "Drupal\contacts\Form\ContactEntityDeleteForm",
 *     },
 *     "access" = "Drupal\contacts\ContactEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\contacts\ContactEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "contact_entity",
 *   data_table = "contact_entity_field_data",
 *   revision_table = "contact_entity_revision",
 *   revision_data_table = "contact_entity_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer contact entity entities",
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
 *     "canonical" = "/admin/structure/contact_entity/{contact_entity}",
 *     "add-form" = "/admin/structure/contact_entity/add",
 *     "edit-form" = "/admin/structure/contact_entity/{contact_entity}/edit",
 *     "delete-form" = "/admin/structure/contact_entity/{contact_entity}/delete",
 *     "version-history" = "/admin/structure/contact_entity/{contact_entity}/revisions",
 *     "revision" = "/admin/structure/contact_entity/{contact_entity}/revisions/{contact_entity_revision}/view",
 *     "revision_revert" = "/admin/structure/contact_entity/{contact_entity}/revisions/{contact_entity_revision}/revert",
 *     "revision_delete" = "/admin/structure/contact_entity/{contact_entity}/revisions/{contact_entity_revision}/delete",
 *     "translation_revert" = "/admin/structure/contact_entity/{contact_entity}/revisions/{contact_entity_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/contact_entity",
 *   },
 *   field_ui_base_route = "contact_entity.settings"
 * )
 */
class ContactEntity extends RevisionableContentEntityBase implements ContactEntityInterface {

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

    // If no revision author has been set explicitly, make the contact_entity owner the
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
   * Custom getters and setters for the Contact entity.
   */

  /**
   * {@inheritdoc}
   */
  public function getCrelateId() {
    return $this->get('crelate_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getFirstName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFirstName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLastName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLastName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getHotCandidate() {
    return $this->get('hot_candidate')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setHotCandidate($hot_candidate) {
    $this->set('hot_candidate', $hot_candidate ? TRUE : FALSE);
    return $this;
  }

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
      ->setDescription(t('The user ID of author of the Contact entity entity.'))
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
      ->setDescription(t('The name of the Contact entity entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
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
      ->setDescription(t('A boolean indicating whether the Contact entity is published.'))
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
     * e.g: https://app.crelate.com/go#stage/_Contacts/DefaultView/7b59bb80-35ff-48a1-a94c-a83b00ee915e
     * where the ID is the Crelate ID for that contact (VICE).
     */
    $fields['crelate_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Crelate ID'))
      ->setDescription(t('Unique identifier coming from crelate.com'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'settings' => array(
          'display_label' => TRUE,
        ),
      ))
      ->setDisplayOptions('view', array(
        'type' => 'string',
      ))
      ->setDisplayConfigurable('form', FALSE)
      ->setRequired(TRUE)
      ->setReadOnly(TRUE);

    $fields['first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First Name'))
      ->setDescription(t('Contact\'s first name.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'settings' => array(
          'display_label' => FALSE,
        ),
      ))
      ->setDisplayOptions('view', array(
        'type' => 'string',
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setReadOnly(TRUE);

    $fields['last_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last Name'))
      ->setDescription(t('Contact\'s last name.'))
      ->setRevisionable(FALSE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'settings' => array(
          'display_label' => FALSE,
        ),
      ))
      ->setDisplayOptions('view', array(
        'type' => 'string',
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setReadOnly(TRUE);

    $fields['hot_candidate'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Hot Candidate'))
      ->setDescription(t('Indicates whether the Contact is considered a hot candidate for Averity.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['tech_stack'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Technology Stacks'))
      ->setDescription(t('Technology Stacks the contact have experience with.'))
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default:taxonomy_term')
      ->setSetting('handler_settings', ['target_bundles' => ['tech_stack' => 'tech_stack']])
      ->setDisplayOptions('view', [
        'label'  => 'hidden',
        'type'   => 'tech_stack',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', array(
        'type'     => 'entity_reference_autocomplete',
        'weight'   => 5,
        'settings' => array(
          'match_operator'    => 'CONTAINS',
          'size'              => '60',
          'autocomplete_type' => 'tech_stack',
          'placeholder'       => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['industry'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Industry'))
      ->setDescription(t('Industry(ies) identified as contact\'s preferences.'))
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default:taxonomy_term')
      ->setSetting('handler_settings', ['target_bundles' => ['industry' => 'industry']])
      ->setDisplayOptions('view', [
        'label'  => 'hidden',
        'type'   => 'industry',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', array(
        'type'     => 'entity_reference_autocomplete',
        'weight'   => 5,
        'settings' => array(
          'match_operator'    => 'CONTAINS',
          'size'              => '60',
          'autocomplete_type' => 'industry',
          'placeholder'       => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    /**
     * populated via the Crelate ID field above
     * format : https://app.crelate.com/go#stage/_Contacts/DefaultView/CRELATE_ID
     */
    $fields['url'] = BaseFieldDefinition::create('uri')
      ->setLabel(t('Crelate Link'))
      ->setDescription(t('The crelate.com link back to the contact default display page.'))
      ->setSetting('max_length', 2048)
      ->setSetting('case_sensitive', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
