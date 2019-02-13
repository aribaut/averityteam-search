<?php

namespace Drupal\accounts;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Account entity entities.
 *
 * @ingroup accounts
 */
class AccountEntityListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Account entity ID');
    $header['name'] = $this->t('Name');
    // custom fields
    $header['url'] = $this->t('Crelate Link');
    //$header['crelate_id'] = $this->t('Crelate ID');
    $header['tech_stack'] = $this->t('Technology Stack');
    $header['industry'] = $this->t('Industry');
    $header['signed_agreement']  = $this->t('Signed Agreement');
    // $row['interviewed_last'] = $this->dateFormatter->format($entity->getInterviewedLast(), 'short');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\accounts\Entity\AccountEntity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.account_entity.edit_form',
      ['account_entity' => $entity->id()]
    );
    //@todo: Add all custom entity fields defined in AccountEntity Class
    $row['url'] = $entity->getURL();
    //$row['crelate_id'] = $entity->getCrelateId();
    $row['tech_stack'] = $entity->getTechStack();
    $row['industry'] = $entity->getIndustry();
    $row['signed_agreement'] = $entity->getSignedAgreement();
    /*$row['interviewed_last'] = $entity->getInterviewedLast();*/

    return $row + parent::buildRow($entity);
  }

}
