<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 07/06/2015
 * Time: 15:27
 */

namespace Meldon\WotRBundle\Form\Type;
use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Choice;


class CasualtiesDecisionType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $battle = $game->getBattle();
        $c = $battle->getCombatant($game->getCurrentSide());
        $choices = $c->getUnits();
        $hits = $battle->getOpponent($game->getCurrentSide())->getHitsToResolve();
        $builder
            ->add('units', 'entity', array('class' => 'WotRBundle:Unit',
                'choices' => $choices,
                'property' => 'name',
                'expanded' => true,
                'multiple' => true,
                'label' => "You have {$hits} hits to resolve - select the units you'd like to take them against.  Choosing an elite will use a single hit against it."
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'casualtiesDecision';
    }
}