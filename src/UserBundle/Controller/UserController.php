<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Form\Type\UpdateUserType;

class UserController extends Controller
{
    /**
     * @Route("/update_user", name="update_user")
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(new UpdateUserType(), $user);
        
        $form->handleRequest($request);
               
        $em = $this->getDoctrine()->getManager();          
        
        if ( $form->isValid() ) 
        {   
            $newPassword = $form->get('newPassword')->getData();
            // If new password sent then encode it and update user password
            if ( $newPassword ) {
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $newPassword);
                $user->setPassword($encoded);
            }
            $em->persist($user);
            $em->flush();
            $request->getSession()->getFlashBag()->add(
                        'alert-success',
                        'You have updated your details successfully.'
                    );
            return $this->redirect($this->generateUrl('homepage'));
        } elseif ( $form->isSubmitted() )
        {
            $request->getSession()->getFlashBag()->add(
                        'alert-warning',
                        'Please complete the form correctly.'
                    );
        }
        
        // Revert user to its state in the database
        $em->refresh($user);
        
        return $this->render(
                'User/update.html.twig',
                array('form'=>$form->createView()));
    }
}
