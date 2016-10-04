<?php

namespace Drupal\pix_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for Image content.
 *
 * @MigrateSource(
 *   id = "pix_image"
 * )
 */
class PixImage extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('node');
    $query->join('content_type_photo', 'photo', 'node.nid = photo.nid');
    $query
      ->fields('node', array_keys($this->baseFields()))
      ->fields('photo', ['field_gallery_image_fid', 'field_gallery_nid'])
      ->condition('node.type', 'photo', '=');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = $this->baseFields();
    $fields['body'] = $this->t('Node body');
    $fields['teaser'] = $this->t('Node teaser');
    $fields['field_gallery_image_fid'] = $this->t('Image file ID');
    $fields['field_gallery_nid'] = $this->t('Gallery node ID');
    $fields['names'] = $this->t('Name term IDs');

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

    // Get names for this row.
    $name_tids = $this->select('term_node')
      ->fields('term_node', ['tid'])
      ->condition('nid', $row->getSourceProperty('nid'), '=')
      ->condition('vid', $row->getSourceProperty('vid'), '=')
      ->execute()
      ->fetchCol();
    $row->setSourceProperty('names', $name_tids);

    return parent::prepareRow($row);
  }

}
