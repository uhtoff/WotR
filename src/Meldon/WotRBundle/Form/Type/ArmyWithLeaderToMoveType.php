<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 10/06/2015
 * Time: 22:40
 */

namespace Meldon\WotRBundle\Form\Type;


use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class ArmyWithLeaderToMoveType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $choices = $game->getLocationsWithUnits($game->getCurrentSide(), true, true);
        $builder
            ->add('differentDie',new ReturnType(),array(
                'label' => 'Choose a different action die'
            ))
            ->add('source', 'entity', array('class' => 'WotRBundle:Location',
                'choices' => $choices,
                'property' => 'getNameWithUnits',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Please select the location you\'d like to move units from',
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');

    }
    public function getName()
    {
        return 'ArmyWithLeaderToMoveType';
    }

}