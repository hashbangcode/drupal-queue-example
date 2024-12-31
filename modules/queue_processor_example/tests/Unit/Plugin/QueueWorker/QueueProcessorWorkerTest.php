<?php

declare(strict_types=1);

namespace Drupal\Tests\queue_processor_example\Unit\Plugin\QueueWorker;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Tests\UnitTestCase;
use Drupal\queue_processor_example\Plugin\QueueWorker\QueueProcessorWorker;

/**
 * Test the functionality of the queue processor worker.
 *
 * @group drupal_queue_examples
 */
class QueueProcessorWorkerTest extends UnitTestCase {

  use StringTranslationTrait;

  /**
   * The data is processed when appropriate.
   *
   * @covers ::processItem
   */
  public function testProcessItem() {
    $container = new ContainerBuilder();
    $container->set('string_translation', $this->getStringTranslationStub());
    \Drupal::setContainer($container);

    $logger = $this->createMock('\Drupal\Core\Logger\LoggerChannelInterface');
    $processorWorker = new QueueProcessorWorker([], 'queue_processor_example', []);
    $processorWorker->setLoggerService($logger);

    $data = new \stdClass();
    $data->id = 123;

    $return = $processorWorker->processItem($data);
    $this->assertNull($return);
  }

}
