<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 09/06/2015
 * Time: 22:00
 */

namespace Meldon\WotRBundle\Form\Type;


use Doctrine\ORM\EntityManager;
use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChooseLeaderAttackingLocationType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $choices = $game->getLocationsWithUnitsAbleToAttack($game->getCurrentSide(), true);
        $builder
            ->add('differentDie',new ReturnType(),array(
                'label' => 'Choose a different action die'
            ))
            ->add('source', 'entity', array('class' => 'WotRBundle:Location',
                'choices' => $choices,
                'property' => 'getNameWithUnits',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Please select the location you\'d like to attack from',
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'chooseLeaderAttackingLocation';
    }

}