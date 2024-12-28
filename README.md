# Drupal Queue Example

Examples of the Drupal Queue API in action.

Turn on the root Drupal Queue Examples module to create the root menu. Then,
enable the sub-modules you want to see the code in action.

As a rule, no data will be deleted or changed unless made explicitly clear in
the interfaces. This means you can experiment as much as you need to without
fear of changing your current site. It is recommended to install this module
onto a development site just in case.

# Sub Modules

## Queue Simple Example

A simple queue implementation. The stdClass object is used to inject data into
the queue.

Form path: `/drupal-queue-examples/queue-simple-example`

Click the "Add to queue" button to add 100 items to a queue called
`queue_simple_example`.

Run cron normally to process the queue items.

## Queue Class Example

An example of using a queue data transfer class to handle the queue data.

Form path: `/drupal-queue-examples/queue-class-example`

Click the "Add to queue" button to add 100 items to a queue called
`queue_class_example`.

Run cron normally to process the queue items.

## Queue Processor Example

An example of adding items to a queue and then using a custom processor to
process the queue items.

Form path: `/drupal-queue-examples/queue-processor-example`

The hook_queue_info_alter() is used to turn off cron running for the queue.

## Queue Drush Example

As example of adding items to a queue inside a Drush command.

To add items to the `queue_drush_example` queue use the populate command.

`drush queue_drush_example:populate`

You can process the queue using the `drush cron` command.

You can also use the report command to see how many items are in the queue.

`drush queue_drush_example:report`

## Queue Memory Example

An example of using the memory queue that is built into Drupal. This queue is a
good way to understand how the queue system works without using a database. The
main difference is that everything must be handled in a single page load. As
the cron handler is not used for this queue a processor is used to process
the items in the queue directly.

This queue is used by the batch API when using a progressive batch run.

Form path: `/drupal-queue-examples/queue-memory-example`

## Queue Custom Example

This is an implementation of a custom queue, along with a custom queue factory.
The queue created extends the DatabaseQueue, which is a form of reliable
queue.

Form path: `/drupal-queue-examples/queue-custom-example`

The form itself is self contained and will use the custom queue factory to
setup the queue. The custom queue works in the same way as the default database
queue, but will store the data in a different table (called queue_custom).

A couple of Drush commands exist in order to show the use of the queue settings
when using custom queues. Set the following in your Drupal settings.php before
trying to use these commands.

```
$settings['queue_service_queue_custom_example'] =
  'queue_custom_example.database';
```

You can then use the following command to see how many items exist in the
`queue_custom_example` queue.

`drush queue_custom_example:report`

You can also populate the queue with data using the populate command.

`drush queue_custom_example:populate`

This queue cannot be processed via the cron command.
