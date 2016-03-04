<?php
/**
 * Contains class AwbCreatedEvent
 *
 * @package     Konekt\CourierBundle
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-02
 * @version     2016-03-02
 */

namespace Konekt\CourierBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event class which has to be dispatched at AWB creation.
 */
class AwbCreatedEvent extends Event
{
    /**
     * @var string
     */
    private $packageId;

    /**
     * @var string
     */
    private $awbNumber;

    /**
     * AwbCreatedEvent constructor.
     *
     * @param $packageId
     * @param $awbNumber
     */
    public function __construct($packageId, $awbNumber)
    {
        $this->packageId = $packageId;
        $this->awbNumber = $awbNumber;
    }

    /**
     * Returns the package ID.
     *
     * @return mixed
     */
    public function getPackageId()
    {
        return $this->packageId;
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