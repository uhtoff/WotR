<?php
namespace Meldon\WotRBundle\Form\Type;

use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReturnType extends AbstractType {
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'label' => 'Return',
            'required' => false,
            'attr' => ( array('class' => 'toggleTriggerReverse', 'data-toggle' => 'actionChoice' ) )
        ));
    }
    public function getParent()
    {
        return 'checkbox';
    }
    public function getName()
    {
        return 'return';
    }
}