<?php

declare(strict_types=1);

namespace Drupal\queue_custom_example\Form;

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
   * @var \Drupal\queue_custom_example\CustomQueueProcessorInterface
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
    $instance->queueFactory = $container->get('queue_custom_example.database');
    $instance->queueProcessor = $container->get('queue_custom_example.queue_runner');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['help'] = [
      '#markup' => $this->t('Submit this form to add 100 items into a queue and then process it.'),
    ];

    /** @var \Drupal\Core\Queue\QueueInterface $queue */
    $queue = $this->queueFactory->get('queue_custom_example');
    $form['current_number'] = [
      '#markup' => $this->t('<p>There are currently @items items in the queue.</p>', ['@items' => $queue->numberOfItems()]),
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'populate' => [
        '#type' => 'submit',
        '#op' => 'populate',
        '#value' => $this->t('Add to queue'),
      ],
      'process' => [
        '#type' => 'submit',
        '#op' => 'process',
        '#value' => $this->t('Process queue using custom handler'),
      ],
    ];

    $form['description'] = [
      '#markup' => $this->t('<p>Note that this queue will not be processed by the cron runner.</p>'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $operation = $form_state->getTriggeringElement()['#op'] ?? 'save';

    switch ($operation) {
      case 'populate':

        /** @var \Drupal\Core\Queue\QueueInterface $queue */
        $queue = $this->queueFactory->get('queue_custom_example');

        for ($i = 0; $i < 100; $i++) {
          $item = new \stdClass();
          $item->id = $i;
          $queue->createItem($item);
        }
        break;

      case 'process':

        if ($this->queueProcessor->queueHasItems('queue_custom_example') === FALSE) {
          $this->messenger()->addStatus('The queue is empty.');
          break;
        }

        $this->queueProcessor->run('queue_custom_example');
        $this->messenger()->addStatus('Queue processed.');

        break;
    }
  }

}
