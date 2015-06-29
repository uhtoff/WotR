<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 13/06/2015
 * Time: 12:01
 */

namespace Meldon\WotRBundle\Form\Type;


use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AttackingUnitsWithLeaderType extends AttackingUnitsType {
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
            $context->buildViolation('You must choose at least one leader to attack with.')
                ->addViolation();
        }
    }
    public function getName()
    {
        return 'AttackingUnitsWithLeaderType';
    }

}