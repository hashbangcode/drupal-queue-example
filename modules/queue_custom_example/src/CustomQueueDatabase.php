<?php

declare(strict_types=1);

namespace Drupal\queue_custom_example;

use Drupal\Core\Queue\DatabaseQueue;

/**
 * A custom queue based off of the core database queue.
 */
class CustomQueueDatabase extends DatabaseQueue {

  /**
   * The database table name.
   */
  public const TABLE_NAME = 'queue_custom';

}
