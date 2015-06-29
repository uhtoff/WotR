<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;


class FellowshipCompanionDestinationType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }

    /**
     * @TODO Restrict Companion to army if last known location under siege
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        /** @var Location $attLoc */
        $fsLoc = $game->getFellowship()->getLocation();
        $comps = $game->getCurrentAction()->getData()['units'];
        $distance =  $game->getFellowship()->getProgess() + max($comps->map(function($c){return $c->getLevel();})->toArray());
        $choices = $fsLoc->getDestinations($distance);
        $builder
            ->add('return','checkbox',array(
                'label' => 'Choose different companions',
                'required' => false,
                'attr' => ( array('class' => 'toggleTriggerReverse', 'data-toggle' => 'actionChoice' ) )
            ))
            ->add('destination', 'entity', array('class' => 'WotRBundle:Location',
                'choices' => $choices,
                'property' => 'details.name',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Please select the location you\'d like to move the group to -',
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'FellowshipCompanionDestination';
    }
}