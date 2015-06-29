<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChooseEventDeckType extends AbstractType { 
    private $game;
    public function __construct(Game $game)
    {
        $this->game = $game;
    }
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $game = $this->game;
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $choices = $game->getCardGroups()
                ->matching(
                    $criteria->where(
                        $expr->andX(
                            $expr->eq('groupTypeName','Deck'),
                            $expr->eq('side',$game->getCurrentSide()),
                            $expr->gte('numCards',0)
                        )
                    )    
                );
        $builder
                ->add('deck', 'entity', array(
                    'class' => 'WotRBundle:CardGroup',
                    'choices' => $choices,
                    'property' => 'details.name', 
                    'expanded' => false, 
                    'multiple' => false,
                    'label' => 'Please select the deck you\'d like to draw a card from - '
                    )
                );
        $builder
            ->add('Submit','submit');
    }
    public function getName()
    { 
        return 'chooseEventDeck'; 
    }
}