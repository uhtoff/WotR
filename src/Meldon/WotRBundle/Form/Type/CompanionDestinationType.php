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

class CompanionDestinationType extends AbstractType {
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
        $comps = $game->getCurrentAction()->getData()['units'];
        $distance = max($comps->map(function($c){return $c->getLevel();})->toArray());
        $choices = $game->getUnits()->get($comps->first()->getId())->getLocation()->getDestinations($distance);
        $builder
            ->add('return',new ReturnType() )
            ->add('destination','entity',array('class' => 'WotRBundle:Location',
                'choices' => $choices,
                'property' => 'details.name',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Where would you like to move the companions to?',
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )) )
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'CompanionDestinationType';
    }

}