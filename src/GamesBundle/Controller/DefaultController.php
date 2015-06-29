<?php

namespace GamesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use GamesBundle\Entity\Boardgame;
use GamesBundle\Entity\Game;
use GamesBundle\Entity\Player;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {        
        $login = $this->createFormBuilder()
                ->setAction($this->generateUrl('login_check'))
                ->add('_username','text')
                ->add('_password','password')
                ->add('save','submit',array('label'=>'Log in'))
                ->getForm();      
        
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                Security::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(Security::AUTHENTICATION_ERROR)) {
            $error = $session->get(Security::AUTHENTICATION_ERROR);            
            $session->remove(Security::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }
        
        if ( $error ) {
            $request->getSession()->getFlashBag()->add(
                        'alert-warning',
                        $error
                    );
        }
            
        return $this->render('Games/index.html.twig', 
                array('form'=>$login->createView()));
    }
    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
    }
    /**
     * @Route("/select", name="select")
     */
    public function selectAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
//        $repo = $this->getDoctrine()
//                ->getRepository('GamesBundle:Boardgame')
//                ->findAll();
        $games = $this->getDoctrine()
                ->getRepository('GamesBundle:Game')
                ->findAll();
        $query = $em->createQuery('SELECT b, g, p, u FROM GamesBundle:Boardgame b JOIN b.games g JOIN g.players p JOIN p.user u WHERE u.id = ' . $user->getID());
        $boardgames = $query->getResult();
                
        return $this->render(
                'Games/select.html.twig', 
                array( 'boardgames' => $boardgames, 'gameRef' => $games ) );
    }
    /**
     * @Route("/new_game", name="new_game")
     */
    public function newGameAction()
    {
        return $this->render(
                'Games/index.html.twig');
    }
}
