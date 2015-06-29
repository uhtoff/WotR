<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 13/06/2015
 * Time: 18:19
 */

namespace Meldon\WotRBundle\Form\Type;


use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class SendMessageType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('message','textarea',array(
            'label' => 'Type your message below -'
        ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'SendMessageType';
    }

}