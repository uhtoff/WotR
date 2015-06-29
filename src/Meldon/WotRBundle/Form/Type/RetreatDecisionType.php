<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 07/06/2015
 * Time: 19:14
 */

namespace Meldon\WotRBundle\Form\Type;


use Doctrine\ORM\EntityManager;
use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RetreatDecisionType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $choices = $game->getBattle()->getDefender()->getLocation()->getDestinations(1, NULL, true)
            ->filter(
                function ($e) use ($game) {
                    return !$game->getLocationsWithUnits($game->getOpponentSide())->contains($e);
                }
            );
        $builder
            ->add('retreat', 'checkbox', array(
                'label' => 'Would you like to retreat?',
                'required' => false,
                'attr' => ( array('class' => 'toggleTrigger', 'data-toggle' => 'actionChoice' ) ) ) )
            ->add('destination', 'entity', array('class' => 'WotRBundle:Location',
                'choices' => $choices,
                'property' => 'getNameWithUnits',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Please select the location you\'d like to retreat to',
                'label_attr' => array('class' => 'actionChoice startHidden' ),
                'attr' => ( array('class' => 'actionChoice startHidden') )
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'chooseArmyDestinationType';
    }
}