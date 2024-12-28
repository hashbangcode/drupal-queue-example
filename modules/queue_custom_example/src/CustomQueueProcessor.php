<?php

declare(strict_types=1);

namespace Drupal\queue_custom_example;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Queue\DelayableQueueInterface;
use Drupal\Core\Queue\DelayedRequeueException;
use Drupal\Core\Queue\QueueWorkerManagerInterface;
use Drupal\Core\Queue\RequeueException;
use Drupal\Core\Queue\SuspendQueueException;
use Drupal\Core\Utility\Error;
use Psr\Log\LoggerInterface;

/**
 * Runs the queue with a set time delay.
 *
 * Used to run the queue items outside the cron runner.
 */
class CustomQueueProcessor implements CustomQueueProcessorInterface {

  /**
   * Creates a QueueProcessor object.
   *
   * @param \Drupal\queue_custom_example\CustomQueueDatabaseFactory $queueFactory
   *   The custom queue factory service.
   * @param \Drupal\Core\Queue\QueueWorkerManagerInterface $queueWorkerManager
   *   The queue worker manager service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Psr\Log\LoggerInterface $logger
   *   The queue_processor_example logging service.
   */
  public function __construct(
    protected CustomQueueDatabaseFactory $queueFactory,
    protected QueueWorkerManagerInterface $queueWorkerManager,
    protected TimeInterface $time,
    protected LoggerInterface $logger,
  ) {}

  /**
   * {@inheritDoc}
   */
  public function queueHasItems(string $type):bool {
    $queue = $this->queueFactory->get($type);

    if ($queue->numberOfItems() === 0) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritDoc}
   */
  public function run(string $type, int $time = 10) {
    $queue = $this->queueFactory->get($type);
    $worker = $this->queueWorkerManager->createInstance($type);

    if ($queue->numberOfItems() === 0) {
      // Queue is empty, so return here.
      return;
    }

    $pluginDefinition = $worker->getPluginDefinition();
    $leaseTime = $pluginDefinition['cron']['time'] ?? $time;

    $end = $this->time->getCurrentTime() + $leaseTime;
    while ($this->time->getCurrentTime() < $end && ($item = $queue->claimItem($leaseTime))) {
      try {
        $worker->processItem($item->data);
        $queue->deleteItem($item);
      }
      catch (DelayedRequeueException $e) {
        // The worker requested the task not be immediately re-queued.
        // - If the queue doesn't support ::delayItem(), we should leave the
        // item's current expiry time alone.
        // - If the queue does support ::delayItem(), we should allow the
        // queue to update the item's expiry using the requested delay.
        if ($queue instanceof DelayableQueueInterface) {
          // This queue can handle a custom delay; use the duration provided
          // by the exception.
          $queue->delayItem($item, $e->getDelay());
        }
      }
      catch (RequeueException) {
        // The worker requested the task be immediately requeued.
        $queue->releaseItem($item);
      }
      catch (SuspendQueueException $e) {
        // If the worker indicates the whole queue should be skipped, release
        // the item and go to the next queue.
        $queue->releaseItem($item);

        $this->logger->debug('A worker for @queue queue suspended further processing of the queue.', [
          '@queue' => $worker->getPluginId(),
        ]);

        // Skip to the next queue.
        throw $e;
      }
      catch (\Exception $e) {
        // In case of any other kind of exception, log it and leave the item
        // in the queue to be processed again later.
        Error::logException($this->logger, $e);
      }
    }
  }

}
