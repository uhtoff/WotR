<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BattleCardType extends AbstractType {
    private $game;
    public function __construct(Game $game)
    {
        $this->game = $game;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;

        $choices = $game->getHand($game->getCurrentSide())
            ->getCardsByType(NULL,false);

        $builder
            ->add('noBattleCard',new ReturnType(), array(
                'label' => 'Play no battle card'
            ))
            ->add('card', new CardType(), array(
                'choices' => $choices,
                'multiple' => false,
                'property' => 'details.battlePopover',
                'label' => 'Please select the card that you wish to play for its battle event -',
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'battleCard';
    }
}