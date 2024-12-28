<?php

declare(strict_types=1);

namespace Drupal\queue_custom_example;

use Drupal\Core\Queue\QueueDatabaseFactory;

/**
 * A queue factory class to create the CustomQueueDatabase queue.
 */
class CustomQueueDatabaseFactory extends QueueDatabaseFactory {

  /**
   * {@inheritdoc}
   */
  public function get($name) {
    return new CustomQueueDatabase($name, $this->connection);
  }

}
