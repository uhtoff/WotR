<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DeclareFellowshipType extends AbstractType {
    private $game;
    public function __construct(Game $game)
    {
        $this->game = $game;
    }
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $game = $this->game;
        $fs = $game->getFellowship();
        $builder
                ->add('declare', 'checkbox', array(
                    'label' => 'Declare the fellowship?',
                    'required' => false,
                    'attr' => ( array('class' => 'toggleTrigger', 'data-toggle' => 'declareDest' ) ) ) )
                ->add('location', 'entity', array(
                    'class' => 'WotRBundle:Location',
                    'choices' => $fs->getLocation()->getDestinations($fs->getProgress()),
                    'property' => 'details.name', 
                    'expanded' => false, 
                    'multiple' => false,
                    'label' => 'Please select where you would like to declare the fellowship',
                    'label_attr' => array('class' => 'declareDest startHidden'),
                    'attr' => array('class' => 'declareDest startHidden' ) ) ) 
                ->add('Submit','submit');
    }
    public function getName()
    { 
        return 'declareDecision'; 
    }
}
