<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 17/06/2015
 * Time: 10:27
 */

namespace Meldon\WotRBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * @TODO Add ability to use guide abilities
 * Class HuntTileDecisionType
 * @package Meldon\WotRBundle\Form\Type
 */
class HuntTileDecisionType extends WotRType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $tile = $game->getHuntTileToResolve();
        $damage = $game->getHuntTileDamage($tile);
        $guide = $game->getFellowship()->getGuide()->getNameWithLevel();
        $huntChoice = array(
            'guide' => "Take hunt damage onto {$guide}",
            'companion' => 'Take hunt damage onto a random companion',
            'ring' => 'Use the One Ring (suffering corruption)'
        );
        $builder
            ->add('huntResolve', 'choice', array(
                'choices' => $huntChoice,
                'expanded' => true,
                'multiple' => false,
                'label' => "How would you like to deal with the {$damage} corruption from the {$tile->getName()} tile?"
            ) )
            ->add('huntDamage', 'hidden', array(
                'data' => $damage
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'HuntTileDecision';
    }
}