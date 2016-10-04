<?php

namespace Drupal\pix_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for Name terms.
 *
 * @MigrateSource(
 *   id = "pix_name"
 * )
 */
class PixName extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('term_data')
      ->fields('term_data', array_keys($this->fields()))
      ->condition('vid', '1', '=');
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'tid' => $this->t('Term ID'),
      'vid' => $this->t('Vocabulary ID'),
      'name' => $this->t('Term Name'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'tid' => [
        'type' => 'integer',
      ],
    ];
  }

}
