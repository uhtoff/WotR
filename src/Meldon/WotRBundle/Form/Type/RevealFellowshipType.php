<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 17/06/2015
 * Time: 11:44
 */

namespace Meldon\WotRBundle\Form\Type;


use Symfony\Component\Form\FormBuilderInterface;

class RevealFellowshipType extends WotRType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $fs = $game->getFellowship();
        $locs = $fs->getLocation()->getDestinations($fs->getProgress());
        $choices = $locs->filter(
            function ($l) {
                return !$l->getSide() || !($l->getSide()->isFP() && ( $l->getStronghold() || $l->getCity() ));
            }
        );
        $builder
            ->add('location', 'entity', array(
                'class' => 'WotRBundle:Location',
                'choices' => $choices,
                'property' => 'details.name',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Please select where you would like to reveal the fellowship'))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'RevealDecision';
    }
}