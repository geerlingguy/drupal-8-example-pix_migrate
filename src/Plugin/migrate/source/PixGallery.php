<?php

namespace Drupal\pix_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for Gallery content.
 *
 * @MigrateSource(
 *   id = "pix_gallery"
 * )
 */
class PixGallery extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('node')
      ->fields('node', array_keys($this->baseFields()))
      ->condition('type', 'gallery', '=');
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = $this->baseFields();
    $fields['body'] = $this->t('Node body');
    $fields['teaser'] = $this->t('Node teaser');
    $fields['images'] = $this->t('Images referencing the gallery');

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function baseFields() {
    $fields = [
      'nid' => $this->t('Node ID'),
      'vid' => $this->t('Node revision ID'),
      'title' => $this->t('Node Title'),
      'uid' => $this->t('Author user ID'),
      'created' => $this->t('Created date UNIX timestamp'),
      'changed' => $this->t('Updated date UNIX timestamp'),
      'status' => $this->t('Node publication status'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'nid' => [
        'type' => 'integer',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    // Get Node revision body and teaser/summary value.
    $revision_data = $this->select('node_revisions')
      ->fields('node_revisions', ['body', 'teaser'])
      ->condition('nid', $row->getSourceProperty('nid'), '=')
      ->condition('vid', $row->getSourceProperty('vid'), '=')
      ->execute()
      ->fetchAll();
    $row->setSourceProperty('body', $revision_data[0]['body']);
    $row->setSourceProperty('teaser', $revision_data[0]['teaser']);

    // Get a list of all the image nids that referenced this gallery.
    $image_nids = $this->select('content_type_photo', 'photo')
      ->fields('photo', ['nid'])
      ->condition('field_gallery_nid', $row->getSourceProperty('nid'), '=')
      ->execute()
      ->fetchCol();
    $row->setSourceProperty('images', $image_nids);

    return parent::prepareRow($row);
  }

}
