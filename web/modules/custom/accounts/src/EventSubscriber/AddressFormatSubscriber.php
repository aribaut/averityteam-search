<?php

/**
 * @file
 * Contains \Drupal\accounts\EventSubscriber\AddressFormatSubscriber
 */

namespace Drupal\accounts\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\address\Event\AddressEvents;

class AddressFormatSubscriber implements EventSubscriberInterface {

    static function getSubscribedEvents() {
        $events[AddressEvents::ADDRESS_FORMAT][] = array('onGetDefinition', 0);
        return $events;
    }

    public function onGetDefinition($event) {
        $definition = $event->getDefinition();
        // This makes city (locality) field required and leaves
        // the rest address fields as optional
        //$definition['required_fields'] = ['locality'];
        $definition['required_fields'] = ['locality'];
        $event->setDefinition($definition);
    }

}
