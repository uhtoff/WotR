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

class MusterMinionType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    protected function getChoices()
    {
        return $this->game->getMinionsToRecruit();
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('differentDie', new ReturnType(), array('label' => 'Use a different die'))
            ->add('units','entity', array('class' => 'WotRBundle:Unit',
                'choices' => $this->getChoices(),
                'property' => 'popover',
                'expanded' => true,
                'multiple' => true,
                'label' => "Select a minion to muster -",
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'MusterMinionType';
    }
}