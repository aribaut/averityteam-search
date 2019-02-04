<?php

namespace Drupal\accounts\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\accounts\Entity\AccountEntityInterface;

/**
 * Class AccountEntityController.
 *
 *  Returns responses for Account entity routes.
 */
class AccountEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Account entity  revision.
   *
   * @param int $account_entity_revision
   *   The Account entity  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($account_entity_revision) {
    $account_entity = $this->entityManager()->getStorage('account_entity')->loadRevision($account_entity_revision);
    $view_builder = $this->entityManager()->getViewBuilder('account_entity');

    return $view_builder->view($account_entity);
  }

  /**
   * Page title callback for a Account entity  revision.
   *
   * @param int $account_entity_revision
   *   The Account entity  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($account_entity_revision) {
    $account_entity = $this->entityManager()->getStorage('account_entity')->loadRevision($account_entity_revision);
    return $this->t('Revision of %title from %date', ['%title' => $account_entity->label(), '%date' => format_date($account_entity->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Account entity .
   *
   * @param \Drupal\accounts\Entity\AccountEntityInterface $account_entity
   *   A Account entity  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(AccountEntityInterface $account_entity) {
    $account = $this->currentUser();
    $langcode = $account_entity->language()->getId();
    $langname = $account_entity->language()->getName();
    $languages = $account_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $account_entity_storage = $this->entityManager()->getStorage('account_entity');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $account_entity->label()]) : $this->t('Revisions for %title', ['%title' => $account_entity->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all account entity revisions") || $account->hasPermission('administer account entity entities')));
    $delete_permission = (($account->hasPermission("delete all account entity revisions") || $account->hasPermission('administer account entity entities')));

    $rows = [];

    $vids = $account_entity_storage->revisionIds($account_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\accounts\AccountEntityInterface $revision */
      $revision = $account_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $account_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.account_entity.revision', ['account_entity' => $account_entity->id(), 'account_entity_revision' => $vid]));
        }
        else {
          $link = $account_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.account_entity.translation_revert', ['account_entity' => $account_entity->id(), 'account_entity_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.account_entity.revision_revert', ['account_entity' => $account_entity->id(), 'account_entity_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.account_entity.revision_delete', ['account_entity' => $account_entity->id(), 'account_entity_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['account_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
