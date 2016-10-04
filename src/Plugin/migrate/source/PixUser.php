<?php

namespace Drupal\pix_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for user accounts.
 *
 * @MigrateSource(
 *   id = "pix_user"
 * )
 */
class PixUser extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('users')
      ->fields('users', array_keys($this->fields()))
      ->condition('uid', 0, '>')
      ->condition('uid', 1, '<>');
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'uid' => $this->t('User ID'),
      'name' => $this->t('Username'),
      'pass' => $this->t('Password'),
      'mail' => $this->t('Email address'),
      'created' => $this->t('Account created date UNIX timestamp'),
      'access' => $this->t('Last access UNIX timestamp'),
      'login' => $this->t('Last login UNIX timestamp'),
      'status' => $this->t('Blocked/Allowed'),
      'timezone' => $this->t('Timeone offset'),
      'init' => $this->t('Initial email address used at registration'),
      'timezone_name' => $this->t('Timezone name'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'uid' => [
        'type' => 'integer',
      ],
    ];
  }

}
