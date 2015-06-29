<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\ActionDetails;
use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class ChooseActionType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $game = $this->game;
        $dieChoice = $game->getCurrentAction()->getActionDie();
        $possibleChoices = $this->em->getRepository('WotRBundle:ActionDetails')
                ->getActionsForDice($dieChoice);
        $availableActions = array();

        foreach( $possibleChoices as $choice )
        {
            /** @var ActionDetails $choice */
            if ( $choice->isAvailable($game) )
            {
                $availableActions[] = $choice;
            }
        }
        $builder->add('return', 'checkbox', array(
                    'label' => 'Choose a different die',
                    'required' => false,
                    'attr' => ( array('class' => 'toggleTriggerReverse', 'data-toggle' => 'actionChoice' ) ) ) );
        $builder
                ->add('action', 'entity', array('class' => 'WotRBundle:ActionDetails',
                    'choices' => $availableActions,
                    'property' => 'popover', 
                    'expanded' => true, 
                    'multiple' => false,
                    'label' => 'What action would you like to perform with the ' . $dieChoice->getName() . ' die?',
                    'label_attr' => array('class' => 'actionChoice'),
                    'attr' => array('class' => 'actionChoice' )))
                ->add('Submit','submit');
    }
    public function getName()
    { 
        return 'chooseActionDecision'; 
    }
}
