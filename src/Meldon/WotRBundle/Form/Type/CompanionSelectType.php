<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 09/06/2015
 * Time: 22:40
 */

namespace Meldon\WotRBundle\Form\Type;


use Meldon\WotRBundle\Entity\Game;
use Meldon\WotRBundle\Entity\Unit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CompanionSelectType extends AbstractType {
    private $game;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
    protected function getChoices()
    {
        return $this->game->getCompanions();
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('differentDie', new ReturnType(), array('label' => 'Use a different die'))
            ->add('units','entity', array('class' => 'WotRBundle:Unit',
                'choices' => $this->getChoices(),
                'property' => 'nameWithLocation',
                'expanded' => true,
                'multiple' => true,
                'label' => "Select a group of companions in a single location to move -",
                'label_attr' => array('class' => 'actionChoice'),
                'attr' => ( array('class' => 'actionChoice') )
            ))
            ->add('Submit','submit');
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array('constraints' => new Callback(array($this,'checkSameLocation')))
        );
    }
    public function checkSameLocation($data, ExecutionContextInterface $context)
    {
        $loc = $data['units']->first()->getLocation();
        /** @var Unit $u */
        foreach( $data['units'] as $u ) {
            if ( $u->getLocation() != $loc ) {
                $context->addViolation('All selected Companions must be in the same location.');
                break;
            }
        }
    }
    public function getName()
    {
        return 'CompanionSelectType';
    }
}