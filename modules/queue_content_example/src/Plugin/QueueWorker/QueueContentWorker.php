<?php

declare(strict_types=1);

namespace Drupal\queue_content_example\Plugin\QueueWorker;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\Entity\Node;
use Drupal\queue_content_example\Queue\QueueData;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Queue worker for the queue_content_example.
 *
 * @QueueWorker(
 *   id = "queue_content_example",
 *   title = @Translation("Queue worker for the queue content example."),
 *   cron = {"time" = 60}
 * )
 */
class QueueContentWorker extends QueueWorkerBase implements ContainerFactoryPluginInterface {

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
    $instance->logger = $container->get('logger.channel.queue_content_example');
    return $instance;
  }

  /**
   * {@inheritDoc}
   */
  public function processItem($data) {
    if (!$data instanceof QueueData) {
      return;
    }

    // Process the queue item here and create an article.
    $node = Node::create([
      'type' => 'article',
      'title' => $data->getTitle(),
      'uid' => 1,
      'status' => 1,
    ]);
    $node->save();

    // Create a log message.
    $this->logger->info($this->t('Processed simple queue item @title', ['@title' => $data->getTitle()]));
  }

}
