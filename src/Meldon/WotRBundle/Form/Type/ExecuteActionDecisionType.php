<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class ExecuteActionDecisionType extends AbstractType {
    private $game;
    
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $game = $this->game;
        $actionChoice = $game->getDecision()->getAction();
        
        $availableActions = array();
        $builder->add('return', 'checkbox', array(
                    'label' => 'Choose a different action',
                    'required' => false,
                    'attr' => ( array('class' => 'toggleTriggerReverse', 'data-toggle' => 'actionChoice' ) ) ) );
        $builder->add('returnDice', 'checkbox', array(
                    'label' => 'Choose a different die',
                    'required' => false,
                    'attr' => ( array('class' => 'toggleTriggerReverse', 'data-toggle' => 'actionChoice' ) ) ) );
        if ( $actionChoice->getFormType($game) )
        {
            $formType = 'Meldon\\WotRBundle\\Form\\Type\\' . $actionChoice->getFormType($game) . 'Type';
            $builder->add( 'action', new $formType($game), array(
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => array('class' => 'actionChoice' )
            ) );
        }
        $builder
                ->add('Submit','submit');
    }
    public function getName()
    { 
        return 'executeActionDecision'; 
    }

}
