<?php
/**
 * Contains class AwbCreatorFormResolver
 *
 * @package     Konekt\CourierBundle\Services
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-04-05
 * @version     2016-04-05
 */

namespace Konekt\CourierBundle\Services;


use Konekt\Courier\Common\Exception\InvalidCourierException;
use Konekt\Courier\FanCourier\Bridge\Symfony\FancourierPackageType;
use Konekt\Courier\Sprinter\Bridge\Symfony\Form\PackagePPPType;
use Konekt\Courier\Sprinter\Bridge\Symfony\Form\PackageType;
use Symfony\Component\Form\FormFactoryInterface;

class AwbCreatorFormFactory implements AwbCreatorFormFactoryInterface
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * AwbCreatorFormFactory constructor.
     *
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function createForm($carrierName, $data, $options = [])
    {
        switch ($carrierName) {
            case 'fancourier':
                $form = $this->formFactory->create(new FancourierPackageType(), $data, $options);
                $template = '@KonektCourierFancourier/form.html.twig';
                break;
            case 'sprinter_ppp':
                $form = $this->formFactory->create(new PackagePPPType(), $data, $options);
                $template = '@KonektCourierSprinter/form.html.twig';
                break;
            case 'sprinter':
                $form = $this->formFactory->create(new PackageType(), $data, $options);
                $template = '@KonektCourierSprinter/form.html.twig';
                break;
            default:
                throw new InvalidCourierException("Courier $carrierName not supported");
        }



        return [$form, $template];
    }
}