<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;


class ChooseDefendingLocationType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        /** @var Location $attLoc */
        $attLoc = $game->getLocations()->get(
            $game->getCurrentAction()->getData()['source']->getID());
        $choices = $game->getAdjacentLocationsWithEnemyUnits($attLoc, $game->getCurrentSide());
        $builder
            ->add('return','checkbox',array(
                'label' => 'Choose different units',
                'required' => false,
                'attr' => ( array('class' => 'toggleTriggerReverse', 'data-toggle' => 'actionChoice' ) )
            ))
            ->add('destination', 'entity', array('class' => 'WotRBundle:Location',
                'choices' => $choices,
                'property' => 'getNameWithUnits',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Please select the location you\'d like to attack',
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'chooseDefendingLocation';
    }
}