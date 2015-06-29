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

class FellowshipCompanionsType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    protected function getChoices()
    {
        return $this->game->getFellowship()->getCharactersCanLeave();
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('differentDie', new ReturnType(), array('label' => 'Use a different die'))
            ->add('units','entity', array('class' => 'WotRBundle:Unit',
                'choices' => $this->getChoices(),
                'property' => 'nameWithGuide',
                'expanded' => true,
                'multiple' => true,
                'label' => "Select a group of Companions to leave the Fellowship -",
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'fellowshipCompanionsType';
    }
}