<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChooseNewGuideType extends AbstractType {
    private $game;
    public function __construct(Game $game)
    {
        $this->game = $game;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $fs = $game->getFellowship();
        $builder
            ->add('guide', 'entity', array(
                'class' => 'WotRBundle:Character',
                'choices' => $fs->getPossibleGuide(),
                'property' => 'details.name',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Please select whom you would like to guide the fellowship'))
            ->add('Submit','submit');
    }
    public function getName()
    {
        return 'chooseNewGuideDecision';
    }
}