<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 10/06/2015
 * Time: 22:44
 */

namespace Meldon\WotRBundle\Form\Type;


use Meldon\WotRBundle\Entity\Game;
use Meldon\WotRBundle\Entity\Unit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UnitsToMoveWithLeaderType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $data = $game->getCurrentAction()->getData();
        $dest = $game->getLocations()->get($data['destination']->getID());
        $choices = $game->getLocations()->get($data['source']->getID())->getUnits()->filter(
            function ($e) use ($game) {
                return $e->getLastMoved() != $game->getSubphase();
            }
        );
        $choices = $choices->filter(
        /** @var Unit $u */
            function ($u) use ($dest) {
                return $u->isNationAtWar() || !$dest->getNation() || ( $dest->getNationDetails() == $u->getNation()->getDetails() );
            }
        );
        $builder
            ->add('return', new ReturnType(), array(
                'label' => 'Choose a different location' ) )
            ->add('units', 'entity', array('class' => 'WotRBundle:Unit',
                'choices' => $choices,
                'property' => 'name',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Please select the units you\'d like to move',
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');

    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array('constraints' => new Callback(array($this,'checkLeaderSelected')))
        );
    }
    public function checkLeaderSelected($data, ExecutionContextInterface $context)
    {
        $leaderSelected = false;
        /** @var Unit $u */
        foreach( $data['units'] as $u ) {
            if ( $u->isLeader($this->game) ) {
                $leaderSelected = true;
                break;
            }
        }
        if ( !$leaderSelected ) {
            $context->addViolation('You must choose at least one leader to move.');
        }
    }
    public function getName()
    {
        return 'UnitsToMoveWithLeaderType';
    }

}