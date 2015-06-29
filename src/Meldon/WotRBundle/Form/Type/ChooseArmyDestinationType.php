<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;


class ChooseArmyDestinationType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }

    /**
     * @TODO Don't show impossible destinations (no units at War and destination in other Nation)
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $data = $game->getCurrentAction()->getData();
        $choices = $game->getLocations()->get($data['source']->getID())->getDestinations(1)
            ->filter(
                function ($e) use ($game) {
                    return !$game->getLocationsWithUnits($game->getOpponentSide())->contains($e);
                }
            );
        $builder
            ->add('return', 'checkbox', array(
                'label' => 'Choose a different location',
                'required' => false,
                'attr' => ( array('class' => 'toggleTriggerReverse', 'data-toggle' => 'actionChoice' ) ) ) )
            ->add('destination', 'entity', array('class' => 'WotRBundle:Location',
                'choices' => $choices,
                'property' => 'getNameWithUnits',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Please select the location you\'d like to move the army to',
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'chooseArmyDestinationType';
    }
}