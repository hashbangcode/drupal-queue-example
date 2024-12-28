<?php

declare(strict_types=1);

namespace Drupal\queue_memory_example;

use Drupal\Core\Queue\Memory;

/**
 * Interface for the MemoryQueueProcessor service.
 */
interface MemoryQueueProcessorInterface {

  /**
   * Set the memory queue to be processed.
   *
   * @param \Drupal\Core\Queue\Memory $queue
   *   The queue.
   *
   * @return $this
   *   Return the current object.
   */
  public function setQueue(Memory $queue);

  /**
   * Run the queue with a give time limit.
   *
   * The time limit is defined in the queue worker plugin, but can optionally
   * be passed to this plugin to change the execution time. If the time is
   * not defined at all then it will default to 10 seconds.
   *
   * Much of this code is taken from the core Cron implementation of timed
   * queue workers.
   *
   * @param string $type
   *   The type of queue to run.
   * @param int $time
   *   The amount of time to default the processor to. This is used if there
   *   is no value set for the time to run of the cron plugin.
   *
   * @see \Drupal\Core\Cron::processQueue()
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public function run(string $type, int $time = 10);

  /**
   * Find out if the queue has and items in it.
   *
   * @param string $type
   *   The queue type.
   *
   * @return bool
   *   True if the queue has items to process.
   */
  public function queueHasItems(string $type):bool;

}
