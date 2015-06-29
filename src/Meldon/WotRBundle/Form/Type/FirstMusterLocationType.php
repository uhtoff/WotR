<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 17/06/2015
 * Time: 23:02
 */

namespace Meldon\WotRBundle\Form\Type;


use Symfony\Component\Form\FormBuilderInterface;

class FirstMusterLocationType extends WotRType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $choices = $game->getLocationsToRecruit($game->getCurrentSide());
        $builder
            ->add('firstLocation', 'entity', array('class' => 'WotRBundle:Location',
                'choices' => $choices,
                'property' => 'getNameWithUnits',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Please select the location you\'d like to recruit in'
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'FirstMusterLocation';
    }

}