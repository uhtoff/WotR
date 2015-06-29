<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DiscardCardsType extends AbstractType {
    private $game;
    public function __construct(Game $game)
    {
        $this->game = $game;
    }
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $game = $this->game;
        $builder
                ->add('card', new CardType(), array(
                    'choices' => $game->getHand($game->getCurrentSide())->getCards(),
                    'multiple' => true,
                    'label' => 'Please select the cards that you wish to discard, discarding down to 6 cards maximum -'))
                ->add('Submit','submit');
    }
    public function getName()
    { 
        return 'discardDecision'; 
    }
}

