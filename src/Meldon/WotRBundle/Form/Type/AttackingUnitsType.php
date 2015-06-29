<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Choice;


class AttackingUnitsType extends AbstractType {
    protected $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $data = $game->getCurrentAction()->getData();
        $choices = $game->getLocations()->get($data['source']->getID())->getUnits()->filter(
            function ($e) use ($game) {
                return $e->isNationAtWar();
            }
        );
        $builder
            ->add('return', 'checkbox', array(
                'label' => 'Choose a different location',
                'required' => false,
                'attr' => ( array('class' => 'toggleTriggerReverse', 'data-toggle' => 'actionChoice' ) ) ) )
            ->add('units', 'entity', array('class' => 'WotRBundle:Unit',
                'choices' => $choices,
                'property' => 'name',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Please select the units you\'d like to attack with',
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'attackingUnits';
    }
}