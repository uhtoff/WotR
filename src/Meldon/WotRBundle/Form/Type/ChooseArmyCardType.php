<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChooseArmyCardType extends AbstractType { 
    private $game;
    public function __construct(Game $game)
    {
        $this->game = $game;
    }
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $game = $this->game;

        $choices = $game->getHand($game->getCurrentSide())
                ->getCardsByType('Army',true);
        
        $builder
                ->add('card', new CardType(), array(
                    'choices' => $choices,
                    'multiple' => false,
                    'label' => 'Please select the card that you wish to play for its event -'));
    }
    public function getName()
    { 
        return 'chooseArmyCard'; 
    }
}