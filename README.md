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

# To Do

- Different types of queue, including Memory and Reliable queue.
