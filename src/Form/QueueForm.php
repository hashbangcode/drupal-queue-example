<?php

namespace Drupal\drupal_queue_example\Form;

use Drupal\Core\Queue\QueueInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Queue\QueueFactory;
use Drupal\Core\Queue\QueueFactoryInterface;
use Drupal\Core\Url;

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
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'queue_form_example';
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = new static();
    $instance->queueFactory = $container->get('queue');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['help'] = [
      '#markup' => $this->t('Submit this form to add 100 items into a queue.'),
    ];

    /** @var \Drupal\Core\Queue\QueueInterface $queue */
    $queue = $this->queueFactory->get('queue_example');
    $form['current_number'] = [
      '#markup' => $this->t('<p>There are currently @items items in the queue.</p>', ['@items' => $queue->numberOfItems()]),
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Add to queue'),
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    /** @var \Drupal\Core\Queue\QueueInterface $queue */
    $queue = $this->queueFactory->get('queue_example');

    for ($i = 0; $i < 100; $i++) {
      $item = new \stdClass();
      $item->id = $i;
      $queue->createItem($item);
    }
  }

}
