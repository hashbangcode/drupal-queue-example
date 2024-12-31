<?php

declare(strict_types=1);

namespace Drupal\queue_content_example\Queue;

/**
 * Interface for the QueueData object.
 */
interface QueueDataInterface {

  /**
   * Get the title.
   *
   * @return string
   *   The title.
   */
  public function getTitle(): string;

}
