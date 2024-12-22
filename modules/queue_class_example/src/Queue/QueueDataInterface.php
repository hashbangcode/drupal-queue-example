<?php

declare(strict_types=1);

namespace Drupal\queue_class_example\Queue;

/**
 * Interface for the QueueData object.
 */
interface QueueDataInterface {

  /**
   * Get the ID.
   *
   * @return int
   *   The ID.
   */
  public function getId(): int;

}
