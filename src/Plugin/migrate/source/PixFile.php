<?php

namespace Drupal\pix_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for Files (images only).
 *
 * @MigrateSource(
 *   id = "pix_file"
 * )
 */
class PixFile extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('files')
      ->fields('files', array_keys($this->fields()))
      // Ignore unpublished files.
      ->condition('status', '1', '=')
      // Only interested in JPEG image files; that's the bulk of content.
      ->condition('filemime', 'image/jpeg', '=');
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'fid' => $this->t('File ID'),
      'uid' => $this->t('User ID'),
      'filename' => $this->t('File name'),
      'filepath' => $this->t('File path (in public files dir)'),
      'filemime' => $this->t('File MIME type'),
      'timestamp' => $this->t('File created date UNIX timestamp'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'fid' => [
        'type' => 'integer',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    // Update filepath to remove public:// directory portion.
    $original_path = $row->getSourceProperty('filepath');
    $new_path = str_replace('sites/default/files/', 'public://', $original_path);
    $row->setSourceProperty('filepath', $new_path);

    return parent::prepareRow($row);
  }

}
