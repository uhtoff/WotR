<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CardType extends AbstractType { 
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'class' => 'WotRBundle:Card',
            'property' => 'popover',
            'expanded' => true
        ));
    }
    public function getParent()
    {
        return 'entity';
    }
    public function getName()
    { 
        return 'card'; 
    }
}
