<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AllocateHuntDiceType extends AbstractType {
    private $game;
    public function __construct(Game $game)
    {
        $this->game = $game;
    }
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $game = $this->game;
        $fs = $game->getFellowship();
        $maxDice = min( $game->getNumActionDiceToRoll($game->getCurrentSide()), count($fs->getCharacters()));
        $minDice = $fs->getMoved() ? 1 : 0;
        $diceChoice = range($minDice, $maxDice);
        $builder
                ->add('huntDice', 'choice', array(
                    'choices' => $diceChoice,
                    'expanded' => false, 
                    'multiple' => false,
                    'label' => 'How many dice would you like to allocate to the hunt?',
                    ) )
                ->add('Submit','submit');
    }
    public function getName()
    { 
        return 'allocateHuntDecision'; 
    }
}
