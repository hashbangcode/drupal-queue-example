<?php

declare(strict_types=1);

namespace Drupal\queue_drush_example\Commands;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Queue\QueueFactory;
use Drush\Commands\DrushCommands;

/**
 * Drush commands that add items to a queue.
 */
class QueueCommands extends DrushCommands {

  use StringTranslationTrait;

  /**
   * Logger service.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerChannelFactory;

  /**
   * The queue factory service.
   *
   * @var \Drupal\Core\Queue\QueueFactory
   */
  protected $queueFactory;

  /**
   * Constructs a new BatchCommands object.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerChannelFactory
   *   Logger service.
   * @param \Drupal\Core\Queue\QueueFactory $queueFactory
   *   The queue factory service.
   */
  public function __construct(LoggerChannelFactoryInterface $loggerChannelFactory, QueueFactory $queueFactory) {
    $this->loggerChannelFactory = $loggerChannelFactory;
    $this->queueFactory = $queueFactory;
  }

  /**
   * Populate a queue using a Drush command.
   *
   * @command queue_drush_example:populate
   *
   * @validate-module-enabled queue_drush_example
   *
   * @usage queue_drush_example:populate
   */
  public function populateQueue() {
    /** @var \Drupal\Core\Queue\QueueInterface $queue */
    $queue = $this->queueFactory->get('queue_drush_example');
    for ($i = 0; $i < 100; $i++) {
      $item = new \stdClass();
      $item->id = $i;
      $queue->createItem($item);
    }
    $this->logger()->notice("Queue populated with 100 items.");
  }

  /**
   * Report on the number of items in the queue using Drush.
   *
   * @command queue_drush_example:report
   *
   * @validate-module-enabled queue_drush_example
   *
   * @usage queue_drush_example:report
   */
  public function reportQueue() {
    /** @var \Drupal\Core\Queue\QueueInterface $queue */
    $queue = $this->queueFactory->get('queue_drush_example');

    $args = [
      '@number' => $queue->numberOfItems(),
    ];

    $this->logger()->notice($this->t("The queue_drush_example queue has @number items present.", $args));
  }

}
