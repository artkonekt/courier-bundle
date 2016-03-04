<?php
/**
 * Contains class FancourierPackageType
 *
 * @package     Konekt\CourierBundle
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-02-24
 * @version     2016-02-24
 */

namespace Konekt\CourierBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form for a package object. This object is used for creating the AWB.
 */
class FancourierPackageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('continut', 'text', [
            'label' => 'Referinta interna',
        ]);

        $builder->add('tip', 'choice', [
            'label' => 'Tip Serviciu',
            'choices' => [
                'standard' => 'Standard',
                'cont colector' => 'Cont Colector',
            ],
            'required' => true
        ]);

        $builder->add('nr_colet', 'text', [
            'label' => 'Numar colete',
            'required' => true
        ]);

        $builder->add('greutate', 'text', [
            'label' => 'Greutate',
            'required' => true
        ]);

        $builder->add('plata_expeditii', 'choice', [
            'label' => 'Plata expeditiei la',
            'choices' => [
                'expeditor' => 'Expeditor',
                'destinatar' => 'Destinatar',
            ],
        ]);


        $builder->add('ramburs', 'text', [
            'label' => 'Ramburs numerar',
            'required' => false
        ]);

        $builder->add('plata_ramburs_la', 'choice', [
            'label' => 'Plata rambursului',
            'choices' => [
                '---' => '---',
                'expeditor' => 'Expeditor',
                'destinatar' => 'Destinatar',
            ],
            'data' => 'expeditor',
            'required' => false
        ]);

        $builder->add('observatii', 'textarea', [
            'required' => false
        ]);

        $builder->add('nume_destinatar', 'text', [
            'required' => true
        ]);

        $builder->add('persoana_contact', 'text', [
            'label' => 'Persoana de contact',
            'required' => false
        ]);

        $builder->add('telefon', 'text', [
            'required' => true
        ]);

        $builder->add('judet', 'choice', [
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
            ]
        ]);

        $builder->add('localitate', 'text', [
            'required' => true
        ]);

        $builder->add('strada', 'text', [
            'required' => true
        ]);

        $builder->add('zip', 'text', [
            'label' => 'Cod Postal',
            'required' => false
        ]);

        $builder->add('restituire');

        $builder->add('save', new SubmitType(), array('label' => 'Creeare AWB'));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "fancourier_package";
    }
}