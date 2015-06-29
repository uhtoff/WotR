<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 07/06/2015
 * Time: 16:58
 */

namespace Meldon\WotRBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CeaseAttackDecisionType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $builder
            ->add('cease', 'checkbox', array(
                'required' => false,
                'label' => "Would you like to cease the attack?"
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'ceaseAttackDecision';
    }

}