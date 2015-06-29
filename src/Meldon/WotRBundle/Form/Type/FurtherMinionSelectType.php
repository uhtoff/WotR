<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 09/06/2015
 * Time: 22:40
 */

namespace Meldon\WotRBundle\Form\Type;


use Meldon\WotRBundle\Entity\Game;
use Meldon\WotRBundle\Entity\Unit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class FurtherMinionSelectType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $choices = $game->getMinions();
        $builder
            ->add('complete', new ReturnType(), array('label' => 'Finish moving minions'))
            ->add('units','entity', array('class' => 'WotRBundle:Unit',
                'choices' => $choices,
                'property' => 'nameWithLocation',
                'expanded' => true,
                'multiple' => true,
                'label' => "Select a group of minions to move to a single location -",
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'furtherMinionSelectType';
    }

}