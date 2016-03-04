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

class AwbCreatedEvent extends Event
{
    private $packageId;

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
     * @return mixed
     */
    public function getPackageId()
    {
        return $this->packageId;
    }

    /**
     * @return mixed
     */
    public function getAwbNumber()
    {
        return $this->awbNumber;
    }
}