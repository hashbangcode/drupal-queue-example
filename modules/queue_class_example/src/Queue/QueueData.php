<?php

declare(strict_types=1);

namespace Drupal\queue_class_example\Queue;

/**
 * Stores information as a readonly class for the queue handler.
 */
readonly class QueueData implements QueueDataInterface {

  /**
   * The ID of the queue item.
   *
   * @var int
   */
  protected int $id;

  /**
   * Creates a QueueData object.
   *
   * @param int $id
   *   The ID of the queue item.
   */
  public function __construct(int $id) {
    $this->id = $id;
  }

  /**
   * {@inheritDoc}
   */
  public function getId(): int {
    return $this->id;
  }

}
