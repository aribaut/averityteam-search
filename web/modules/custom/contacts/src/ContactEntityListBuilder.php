<?php

namespace Drupal\contacts;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Contact entity entities.
 *
 * @ingroup contacts
 */
class ContactEntityListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Contact entity ID');
    $header['name'] = $this->t('Name');
    // custom fields
    $header['url'] = $this->t('Crelate Link');
    //$header['crelate_id'] = $this->t('Crelate ID');
    $header['tech_stack'] = $this->t('Technology Stack');
    $header['industry'] = $this->t('Industry');
    $header['hot_candidate']  = $this->t('Hot Candidate');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\contacts\Entity\ContactEntity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.contact_entity.edit_form',
      ['contact_entity' => $entity->id()]
    );
    //@todo: Add all custom entity fields defined in AccountEntity Class
    $row['url'] = $entity->getURL();
    //$row['crelate_id'] = $entity->getCrelateId();
    $row['tech_stack'] = $entity->getTechStack();
    $row['industry'] = $entity->getIndustry();
    $row['hot_candidate'] = $entity->getHotCandidate();

    return $row + parent::buildRow($entity);
  }

}
