<?php

declare(strict_types=1);

namespace Drupal\Tests\queue_memory_example\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test the functionality of the queue memory example form.
 *
 * @group drupal_queue_examples
 */
class QueueFormTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'queue_memory_example',
  ];

  /**
   * The theme to install as the default for testing.
   *
   * @var string
   */
  public $defaultTheme = 'stark';

  /**
   * Test that the queue form adds items to the queue.
   */
  public function testQueueForm() {
    $user = $this->createUser(['access content']);
    $this->drupalLogin($user);

    $this->drupalGet('drupal-queue-examples/queue-memory-example');

    $this->submitForm([], 'Add to queue');
    $this->assertSession()->pageTextContains('Queue processed.');
  }

}
