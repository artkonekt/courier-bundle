<?php
/**
 * Contains interface PackageRegistratorFormResolverInterface
 *
 * @package     Konekt\CourierBundle\Services
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-04-05
 * @version     2016-04-05
 */

namespace Konekt\CourierBundle\Services;


interface AwbCreatorFormFactoryInterface
{
    public function createForm($carrierName, $data, $options = []);
}