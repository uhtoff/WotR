<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 17/06/2015
 * Time: 23:11
 */

namespace Meldon\WotRBundle\Form\Type;


use Symfony\Component\Form\FormBuilderInterface;

class FirstMusterUnitType extends WotRType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $data = $game->getCurrentAction()->getData();
        $choices = $game->getUnitsToRecruit($game->getLocations()->get($data['firstLocation']->getID()));
        $builder
            ->add('return', 'checkbox', array(
                'label' => 'Choose a different location',
                'required' => false,
                'attr' => ( array('class' => 'toggleTriggerReverse', 'data-toggle' => 'actionChoice' ) ) ) )
            ->add('unit', 'entity', array('class' => 'WotRBundle:Unit',
                'choices' => $choices,
                'property' => 'name',
                'expanded' => true,
                'multiple' => false,
                'label' => 'Please select the unit you\'d like to recruit',
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'FirstMusterUnit';
    }

}