<?php

namespace Drupal\accounts;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\taxonomy\Entity\Term;
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
    $header['industry'] = $this->t('Industries');
    //$header['signed_agreement']  = $this->t('Signed Agreement');
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
    /**
     * Technology Stack.
     */
    $tech_stacks = "";
    $all_tech_stacks = $entity->getTechStack();
    // Get array keys
    $arrayKeys = array_keys($all_tech_stacks);
    // Fetch last array key
    $lastArrayKey = array_pop($arrayKeys);
    foreach($all_tech_stacks as $ts => $tech_stack) {
      $term = Term::load($tech_stack->id());
      $tech_stacks .= $term->getName();
      // We append a coma after each term but the last
      if($ts != $lastArrayKey) {
        $tech_stacks .= ', ';
      }
    }
    $row['tech_stack'] = $tech_stacks;
    /**
     * Industry.
     */
    $industries = "";
    $all_industries = $entity->getIndustry();
    // Get array keys
    $arrayKeys = array_keys($all_industries);
    // Fetch last array key
    $lastArrayKey = array_pop($arrayKeys);
    foreach($all_industries as $i => $industry) {
      $term = Term::load($industry->id());
      $industries .= $term->getName();
      // We append a coma after each term but the last
      if($i != $lastArrayKey) {
        $industries .= ', ';
      }
    }
    $row['industry'] = $industries;
    /**
     * Signed Agreement.
     */
    /*
    if ($entity->getSignedAgreement()) {
      $row['signed_agreement'] = 'Yes';
    } else {
      $row['signed_agreement'] = 'No';
    }
    */
    /*$row['interviewed_last'] = $entity->getInterviewedLast();*/

    return $row + parent::buildRow($entity);
  }

}
