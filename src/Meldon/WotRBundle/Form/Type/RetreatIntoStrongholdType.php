<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\Count;

class RetreatIntoStrongholdType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        /** @TODO Exclude leaders
         * */
        $choices = $game->getBattle()->getDefendingUnits();
        $builder
            ->add('retreat', 'checkbox', array(
                'required' => false,
                'label' => 'Would you like to retreat into your Stronghold?'
            ))
            ->add('units','entity', array('class'=>'WotRBundle:Unit',
                'choices'=>$choices,
                'multiple' => true,
                'expanded' => true,
                'property' => 'name',
                'label' => 'Which units would you like to retreat with (max 5 - leaders automatically enter)?',
                'constraints' => array(
                    new Count(array('max'=>5,
                        'maxMessage' => 'There is only room for five units in the Stronghold.'))
                )))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'retreatIntoStronghold';
    }
}