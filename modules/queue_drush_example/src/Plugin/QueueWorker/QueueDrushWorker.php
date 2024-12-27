<?php

declare(strict_types=1);

namespace Drupal\queue_drush_example\Plugin\QueueWorker;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Queue worker for the queue_drush_example.
 *
 * @QueueWorker(
 *   id = "queue_drush_example",
 *   title = @Translation("Queue worker for the queue Drush example."),
 *   cron = {"time" = 60}
 * )
 */
class QueueDrushWorker extends QueueWorkerBase implements ContainerFactoryPluginInterface {

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
    $instance->logger = $container->get('logger.channel.queue_drush_example');
    return $instance;
  }

  /**
   * {@inheritDoc}
   */
  public function processItem($data) {
    // Process the queue item here, and then create a log message.
    $this->logger->info($this->t('Processed simple queue item @id', ['@id' => $data->id]));
  }

}
