<?php

declare(strict_types=1);

namespace Drupal\queue_content_example\Queue;

/**
 * Stores information as a readonly class for the queue handler.
 */
readonly class QueueData implements QueueDataInterface {

  /**
   * The title of the content item.
   *
   * @var string
   */
  protected string $title;

  /**
   * Creates a QueueData object.
   *
   * @param string $title
   *   The title of the content item.
   */
  public function __construct(string $title) {
    $this->title = $title;
  }

  /**
   * {@inheritDoc}
   */
  public function getTitle(): string {
    return $this->title;
  }

}
