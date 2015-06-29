<?php
namespace UserBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class UpdateUserType extends AbstractType { 
    public function buildForm(FormBuilderInterface $builder, array $options) 
    { 
        $builder 
                ->add('forename','text')
                ->add('surname','text')
                ->add('email','email')
                ->add('checkPass','password', 
                        array('label'=>'Confirm password',
                            'constraints' => new UserPassword(),
                            'mapped'=>false))
                ->add('newPassword','repeated', array(
                        'type' => 'password',
                        'invalid_message' => 'The password fields must match.',
                        'required' => false,
                        'first_options' => array('label' => 'Enter new password'),
                        'second_options' => array('label' => 'Re-enter new password'),
                        'mapped' => false,
                        'constraints' => array(
                                    new Length(array('min'=>8)),
                                    new Regex(array('pattern'=>'/password/i',
                                                    'match'=>false,
                                                    'message'=>'The password cannot contain the phrase "password"'))
                                )
                        ))
                ->add('save','submit',array('label'=>'Update details'));
    }
    public function getName()
    { 
        return 'updateUser'; 
    }
}
