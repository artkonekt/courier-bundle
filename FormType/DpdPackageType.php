<?php

/**
 * Contains the DpdPackageType class
 *
 * @package     FormType
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2018-07-04
 */


namespace Konekt\CourierBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class DpdPackageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('continut', 'text', [
            'label' => 'Referinta interna',
        ]);

        $builder->add('save', new SubmitType(), array('label' => 'Creeare AWB'));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "dpd_package";
    }
}