<?php
/**
 * Contains class CourierEvents
 *
 * @package     Konekt\CourierBundle
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-02
 * @version     2016-03-02
 */

namespace Konekt\CourierBundle\Event;

/**
 * Class specifying all events of the bundle.
 */
final class CourierEvents
{
    /**
     * The courier.awb.created event is thrown each time an awb is created.
     *
     * The event listener receives a Konekt\CourierBundle\Event\AwbCreatedEvent instance.
     *
     * @var string
     */
    const AWB_CREATED = 'courier.awb.created';

    /**
     * The courier.awb.delete event is thrown each time an awb is deleted.
     *
     * The event listener receives a Konekt\CourierBundle\Event\AwbDeletedEvent instance.
     *
     * @var string
     */
    const AWB_DELETED = 'courier.awb.deleted';
}