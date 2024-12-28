<?php

declare(strict_types=1);

namespace Drupal\queue_memory_example\Form;

use Drupal\Core\Queue\Memory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form that adds a number of items to a queue.
 */
class QueueForm extends FormBase {

  /**
   * The queue factory service.
   *
   * @var \Drupal\Core\Queue\QueueFactoryInterface
   */
  protected $queueFactory;

  /**
   * The qieie process service.
   *
   * @var \Drupal\queue_memory_example\MemoryQueueProcessorInterface
   */
  protected $queueProcessor;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'queue_simple_example_form';
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = new static();
    $instance->queueFactory = $container->get('queue');
    $instance->queueProcessor = $container->get('queue_memory_example.queue_runner');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['help'] = [
      '#markup' => $this->t('Submit this form to add 100 items into a queue and then process it.'),
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'populate' => [
        '#type' => 'submit',
        '#op' => 'populate',
        '#value' => $this->t('Add to queue, and process'),
      ],
    ];

    $form['description'] = [
      '#markup' => $this->t('<p>Note that this queue is created and processed in a single operation and will not be processed by the cron runner.</p>'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    /** @var \Drupal\Core\Queue\Memory $queue */
    $queue = new Memory('queue_memory_example');
    $queue->createQueue();

    for ($i = 0; $i < 100; $i++) {
      $item = new \stdClass();
      $item->id = $i;
      $queue->createItem($item);
    }

    $this->queueProcessor->setQueue($queue)->run('queue_memory_example');

    $this->messenger()->addStatus('Queue processed.');
  }

}
