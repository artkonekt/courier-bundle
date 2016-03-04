<?php
/**
 * Contains class AwbDeletedEvent
 *
 * @package     Konekt\CourierBundle\Event
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-03-04
 * @version     2016-03-04
 */

namespace Konekt\CourierBundle\Event;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event class which has to be dispatched at AWB deletion.
 */
class AwbDeletedEvent extends Event
{
    /**
     * @var string
     */
    private $awbNumber;

    /**
     * AwbCreatedEvent constructor.
     *
     * @param $awbNumber
     */
    public function __construct($awbNumber)
    {
        $this->awbNumber = $awbNumber;
    }

    /**
     * Returns the number of the AWB.
     *
     * @return mixed
     */
    public function getAwbNumber()
    {
        return $this->awbNumber;
    }
}