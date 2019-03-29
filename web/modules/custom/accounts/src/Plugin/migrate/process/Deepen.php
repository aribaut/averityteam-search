<?php

namespace Drupal\accounts\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Transforms an array of values into an array of associative arrays with one key.
 * E.g. ['alpha', 'beta'] becomes [[value => 'alpha'], [value => 'beta']]
 *
 * Use this plugin to pre-process a numeric/non-associative array for other plugins
 * that requires an associative array as input, such as the sub_process plugin.
 *
 * Available configuration keys:
 * - source: Source property.
 * - key_name: name of the key to be used for the associative sub-arrays, defaults to 'value'
 *
 * Example:
 *
 * @code
 * source:
 *   my_flat_array:
 *     - category1
 *     - category2
 * process:
 *   my_associative_array:
 *     plugin: deepen
 *     source: my_flat_array
 *   field_taxonomy_term:
 *     plugin: sub_process
 *     source: '@my_associative_array'
 *       target_id:
 *         plugin: migration_lookup
 *         migration: my_taxonomy_migration
 *         source: value
 * @endcode
 *
 * @see \Drupal\migrate\Plugin\MigrateProcessInterface
 *
 * @MigrateProcessPlugin(
 *   id = "deepen",
 *   handle_multiples = TRUE
 *  * )
 */
class Deepen extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $key_name = (is_string($this->configuration['key_name']) && $this->configuration['key_name'] != '') ? $this->configuration['key_name'] : 'value';

    if (is_array($value) || $value instanceof \Traversable) {
      $result = [];
      foreach ($value as $sub_value) {
        $result[] = [$key_name => $sub_value];
      }
      return $result;
    }
    else {
      throw new MigrateException(sprintf('%s is not traversable', var_export($value, TRUE)));
    }
  }
}