<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 17/05/2015
 * Time: 20:46
 */

namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;


class ChooseNationToAdvanceType extends AbstractType {
    private $game;
    public function __construct(Game $game)
    {
        $this->game = $game;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $choices = $game->canAdvance();
        $builder
            ->add('differentDie', new ReturnType())
            ->add('nation','entity',array(
                'class' => 'WotRBundle:Nation',
                'choices' => $choices,
                'expanded' => true,
                'property' => 'advanceDisplay',
                'label' => 'Select a Nation to advance -',
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'chooseActionDecision';
    }

}