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
        $builder->add('recipient_phone1_number', 'text', [
            'label' => 'Telefon',
        ]);
        $builder->add('recipient_clientName', 'text', [
            'label' => 'Companie/Nume',
        ]);
        $builder->add('recipient_contactName', 'text', [
            'label' => 'Persoana de contact',
        ]);
        $builder->add('recipient_email', 'text', [
            'label' => 'E-mail',
        ]);

        $builder->add('region', 'choice', [
            'choices' => [
                'Alba' => 'Alba',
                'Arad' => 'Arad',
                'Arges' => 'Argeș',
                'Bacau' => 'Bacău',
                'Bihor' => 'Bihor',
                'Bistrita-Nasaud' => 'Bistrița-Năsăud',
                'Botosani' => 'Botoșani',
                'Brasov' => 'Brașov',
                'Braila' => 'Brăila',
                'Buzau' => 'Buzău',
                'Caras-Severin' => 'Caraș-Severin',
                'Calarasi' => 'Călărași',
                'Cluj' => 'Cluj',
                'Constanta' => 'Constanța',
                'Covasna' => 'Covasna',
                'Dambovita' => 'Dâmbovița',
                'Dolj' => 'Dolj',
                'Galati' => 'Galați',
                'Giurgiu' => 'Giurgiu',
                'Gorj' => 'Gorj',
                'Harghita' => 'Harghita',
                'Hunedoara' => 'Hunedoara',
                'Ialomita' => 'Ialomița',
                'Iasi' => 'Iași',
                'Ilfov' => 'Ilfov',
                'Maramures' => 'Maramureș',
                'Mehedinti' => 'Mehedinți',
                'Mures' => 'Mureș',
                'Neamt' => 'Neamț',
                'Olt' => 'Olt',
                'Prahova' => 'Prahova',
                'Satu Mare' => 'Satu Mare',
                'Salaj' => 'Sălaj',
                'Sibiu' => 'Sibiu',
                'Suceava' => 'Suceava',
                'Teleorman' => 'Teleorman',
                'Timis' => 'Timiș',
                'Tulcea' => 'Tulcea',
                'Vaslui' => 'Vaslui',
                'Valcea' => 'Vâlcea',
                'Vrancea' => 'Vrancea',
                'Bucuresti' => 'București',
            ],
            'label' => 'Judet'
        ]);

        $builder->add('recipient_address_siteName', 'text', [
            'label' => 'Oras',
        ]);

        $builder->add('recipient_address_siteId', 'text', [
            'label' => 'Id Oras',
            'attr' => ['readonly' => true]
        ]);

        $builder->add('recipient_address_streetName', 'text', [
            'label' => 'Adresa',
        ]);


        $builder->add('service_pickupDate', 'hidden');
        $builder->add('service_serviceId', 'hidden');
        $builder->add('service_additionalServices_declaredValue_amount', 'hidden');


        $builder->add('content_parcelsCount', 'text', [
            'label' => 'Numar colete',
        ]);
        $builder->add('content_totalWeight', 'text', [
            'label' => 'Greutate totala',
        ]);
        $builder->add('content_contents', 'hidden');
        $builder->add('content_package', 'hidden');


        $builder->add('shipmentNote', 'text', [
            'label' => 'Nota',
            'required' => false
        ]);
        $builder->add('ref1', 'text', [
            'label' => 'Ref1',
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