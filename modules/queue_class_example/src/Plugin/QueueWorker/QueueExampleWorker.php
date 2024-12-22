<?php

declare(strict_types=1);

namespace Drupal\queue_class_example\Plugin\QueueWorker;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\queue_class_example\Queue\QueueDataInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Queue worker for the queue_class_example.
 *
 * @QueueWorker(
 *   id = "queue_class_example",
 *   title = @Translation("Queue worker for the class queue example."),
 *   cron = {"time" = 60}
 * )
 */
class QueueExampleWorker extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  use StringTranslationTrait;

  /**
   * Logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new self($configuration, $plugin_id, $plugin_definition);
    $instance->logger = $container->get('logger.channel.queue_class_example');
    return $instance;
  }

  /**
   * {@inheritDoc}
   */
  public function processItem($data) {
    if (!($data instanceof QueueDataInterface)) {
      // The only way to remove an item from a queue inside the Drupal cron
      // queue handler is to return silently. If the queue item had no error
      // then cron sees the item as having worked and so it is removed from the
      // queue. This means that if this method receives an object that isn't
      // a QueueDataInterface object then we simply log the error and return.
      $this->logger->error($this->t('Unable to process queue item @id', ['@id' => $data->getId()]));
      return;
    }

    // Process the queue item here.
    // Log the item as having been processed.
    $this->logger->info($this->t('Processed class queue item @id', ['@id' => $data->getId()]));
  }

}
