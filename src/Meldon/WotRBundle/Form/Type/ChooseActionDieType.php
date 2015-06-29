<?php
namespace Meldon\WotRBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChooseActionDieType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->em = $em;
        $this->game = $game;
    }
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $game = $this->game;
        $messageAction = array('message'=>$this->em->getRepository('WotRBundle:ActionDetails')->findOneById(22));
        if ( $game->canPass() ) {
            $builder->add('pass', 'checkbox', array(
                'label' => 'Pass',
                'required' => false,
                'attr' => (array('class' => 'toggleTriggerReverse', 'data-toggle' => 'actionDice'))));
        }
        $builder->add('sendMessage','checkbox', array(
                'label' => 'Send a message to all players',
                'required' => false,
                'attr' => ( array('class' => 'toggleTriggerReverse', 'data-toggle' => 'actionDice' ) ) ) );
        $builder
                ->add('actionDie', 'entity', array('class' => 'WotRBundle:ActionDie',
                    'choices' => $game
                        ->getDicePoolByType($game
                                ->getCurrentSide()
                                ->getAbbreviation()),
                    'property' => 'details.name', 
                    'expanded' => true, 
                    'multiple' => false,
                    'label' => 'Please select the action die you wish to use',
                    'label_attr' => array('class' => 'actionDice'),
                    'attr' => array('class' => 'actionDice' )))
                ->add('Submit','submit');
    }
    public function getName()
    { 
        return 'useActionDieDecision'; 
    }
}

