<?php
/**
 * Contains class CourierEvents
 *
 * @package     Konekt\CourierBundle\Event
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-03-02
 * @version     2016-03-02
 */

namespace Konekt\CourierBundle\Event;


final class CourierEvents
{
    /**
     * The courier.awb.created event is thrown each time an awb is created
     * in the system.
     *
     * The event listener receives a
     * Konekt\CourierBundle\Event\AwbCreatedEvent instance.
     *
     * @var string
     */
    const AWB_CREATED = 'courier.awb.created';
}