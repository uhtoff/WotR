<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 07/06/2015
 * Time: 21:39
 */

namespace Meldon\WotRBundle\Form\Type;


use Doctrine\ORM\EntityManager;
use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AttackerUnitsToAdvance extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $choices = $game->getBattle()->getAttacker()->getUnits();
        $builder
            ->add('noAdvance', new ReturnType(), array(
                'label' => 'Do not advance' ) )
            ->add('units', 'entity', array('class' => 'WotRBundle:Unit',
                'choices' => $choices,
                'property' => 'name',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Please select the units you\'d like to advance',
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'attackerUnitsToAdvance';
    }
}