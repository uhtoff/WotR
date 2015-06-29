<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 09/06/2015
 * Time: 22:57
 */

namespace Meldon\WotRBundle\Form\Type;


use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class MinionDestinationType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }

    /**
     * @TODO Need to check for Mouth of Sauron
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $choices = $game->getLocations()
            ->filter(
                function ($e) use ($game) {
                    return !$game->getLocationsWithUnits($game->getOpponentSide())->contains($e)
                        && !$e->isInStronghold() && !$e->isInMordor() && !( $e->isStronghold() && $e->getSide() == $game->getOpponentSide() );
                }
            );
        $builder
            ->add('return',new ReturnType() )
            ->add('destination','entity',array('class' => 'WotRBundle:Location',
                'choices' => $choices,
                'property' => 'details.name',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Where would you like to move the minions to?',
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )) )
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'minionDestinationType';
    }

}