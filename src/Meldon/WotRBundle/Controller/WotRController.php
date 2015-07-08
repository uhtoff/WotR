<?php
namespace Meldon\WotRBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Meldon\WotRBundle\Entity\Action;
use Meldon\WotRBundle\Entity\ActionStack;
use Meldon\WotRBundle\Entity\Battle;
use Meldon\WotRBundle\Entity\ConnectionDetails;
use Meldon\WotRBundle\Entity\Log;
use Meldon\WotRBundle\Entity\NationCollection;
use Meldon\WotRBundle\Entity\NationCopy;
use Meldon\WotRBundle\Entity\NationDetails;
use Meldon\WotRBundle\Entity\NationDetailsCopy;
use Meldon\WotRBundle\Entity\SubAction;
use Meldon\WotRBundle\Factory\LogFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Meldon\WotRBundle\Entity\Scenario;
use Meldon\WotRBundle\Entity\Card;
use Meldon\WotRBundle\Entity\Game;
use Meldon\WotRBundle\Entity\Nation;
use Meldon\WotRBundle\Entity\Location;
use Meldon\WotRBundle\Entity\Character;
use Meldon\WotRBundle\Entity\Fellowship;
use Meldon\WotRBundle\Entity\ActionDie;
use Meldon\WotRBundle\Entity\CharacterDetails;
use Meldon\WotRBundle\Entity\CardDetails;
use Meldon\WotRBundle\Entity\CardEntryDetails;
use Meldon\WotRBundle\Entity\CardGroup;
use Meldon\WotRBundle\Entity\CardGroupDetails;
use Meldon\WotRBundle\Entity\Connection;
use Meldon\WotRBundle\Entity\DecisionStack;
use Meldon\WotRBundle\Entity\Decision;
use Meldon\WotRBundle\Entity\ElvenRings;
use Meldon\WotRBundle\Entity\Unit;
use Meldon\WotRBundle\Entity\UnitType;
use Meldon\WotRBundle\Entity\UnitEntryDetails;
use Meldon\WotRBundle\Entity\Player;
use Doctrine\Common\Util\Debug;

class WotRController extends Controller
{
    /**
     * @Route("/wotr/test/{id}")
     * @ParamConverter("post", class="WotRBundle:Game", options={"repository_method" = "getCurrentGame"})
     * @param Game $game
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function testAction(Game $game, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
//        $startNations = $em->getRepository('WotRBundle:NationDetails')
//            ->findAll();
//        foreach( $startNations as $sN ) {
//            $n = new NationCopy($sN,$game);
//            $em->persist($n);
//        }
//        $em->flush();
//        exit();
        $nations = $game->getNations();
        foreach( $nations as $n )
        {
            echo $n->getId();
        }

        $nations->filter(
            function ($n) {
                return $n->getName() == 'Dwarves';
            }
        );

        $c = Criteria::create();
        $e = Criteria::expr();
        $c->where(
            $e->eq('name','Dwarves')
        );
        $nations = $nations->matching($c);
        /** @var Nation $n */
        foreach( $nations as $n ) {
            echo $n->getName();
        }
        return new Response('<body>Test</body>');
    }

    /**
     * @Route("/wotr/{id}")
     * @ParamConverter("post", class="WotRBundle:Game", options={"repository_method" = "getCurrentGame"})
     * @param Game $game
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function indexAction(Game $game, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
//        $game->setCurrentPlayer($game->getPlayers()->first());
        // Get next decision, if no decision to make then advance game
        // which runs recursively until it finds a decision
        // @TODO - Could gND recurse to aG itself? Would need to solve how to roll dice within Entity...
        $decision = $this->advanceGame($game);
        // Temporary line to make testing easier
        $game->setCurrentSide($game->getCurrentAction()->getSide());
        if ( $game->getCurrentAction()->getSide() == $game->getCurrentSide() ) {
            $formType = $game->getCurrentAction()->getFormType($game);
            $formClass = '\\Meldon\\WotRBundle\\Form\\Type\\' 
                    . ucfirst($formType) . 'Type';
            $form = $this->createForm(new $formClass($game, $em));

            $form->handleRequest($request);

            if ( $form->isValid() )
            {
                LogFactory::startLog($game);
                $data = $form->getData();
                $game->processDecision($data);
                LogFactory::completeLog($game->getLog());
                $em->flush();
                // This can probably be removed when set up properly to have logged in Players
                $this->advanceGame($game);
                // Redirect here to clear request and prevent double submission warning
                return $this->redirect($this->generateUrl('meldon_wotr_wotr_index',
                        array('id'=>$game->getID())));
            }
            $em->flush();
            return $this->render('WotRBundle:Default:decision.html.twig', 
                    array('game'=> $game,'form'=>$form->createView()));
        } else 
        {
            return $this->render('WotRBundle:Default:oppDecision.html.twig', 
                    array('game'=> $game,'decision'=>$decision));
        }
    }
    /**
     * @Route("/wotr/delete/all")
     * @return Response
     */
    public function deleteAllGamesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $games = $em->getRepository('WotRBundle:Game')->findAll();
        foreach( $games as $game ) {
            $em->remove($game);
        }

        $em->flush();
        return $this->render('WotRBundle:Default:index.html.twig');
    }
    /**
     * @Route("/wotr/delete/{id}")
     * @param Game $game
     * @return Response
     */
    public function deleteGameAction(Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($game);
        $em->flush();
        return $this->render('WotRBundle:Default:index.html.twig');
    }
    /**
     * Advance game, actioning Phase related actions and decisions
     * @param Game $game
     * @return Decision
     */
    protected function advanceGame(Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        // Loop until we have a decision point to go back to the user with
        while( !$game->getDecision() ) {

            $a = $game->getCurrentAction();
            if ($a) {
                if ( $a->getSubAction() ) {
                    LogFactory::startLog($game);
                    if ($a->hasDecision($game)) {
                        $game->setDecision();
                    } else {
                        // Execute also advances subAction
                        $a->execute($game, $this);
                    }
                    LogFactory::completeLog($game->getLog());
                } else {
                    $a->setNextSubAction($game);
                }

            } else {
                // No action so advance Phase to get one
                $game->advancePhase();
            }

            $em->flush();

        }
        return $game->getDecision();
    }
    /**
     * Roll dice for the action phases and add to the game
     * @param Game $game
     */
    public function rollDice(Game $game)
    {   
        $em = $this->getDoctrine()->getManager();
        $diceRoller = $this->get('meldon.dice');
        $sides = $game->getSides();
        foreach( $sides as $side )
        {
            LogFactory::setText("<p><strong>" . $side->getName() . " roll - </strong>");
            $numDice = $game->getNumActionDiceToRoll($side);
            $diceRoll = $diceRoller->roll($numDice, 6, 'ASC');
            $details = $em->getRepository('WotRBundle:ActionDieDetails')
                ->getDetailsArrayByNumber($side);
            foreach( $diceRoll as $dieRoll )
            {
                $aD = new ActionDie($details[$dieRoll],$game);
                $em->persist($aD);
                LogFactory::setText($aD->getName());
            }
            if ( $side->getAbbreviation() === 'S' )
            {
                // Add hunt dice
                for( $i=0; $i<$game->getHuntDice(); $i++ )
                {
                    $aD = new ActionDie($details[6],$game);
                    $em->persist($aD);
                    LogFactory::setText($aD);
                }
            }
            LogFactory::setText("</p>");
        }
        $em->flush();
    }

    public function rollCombatDice(Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        $dR = $this->get('meldon.dice');
        $b = $game->getBattle();
        $a = $b->getAttacker();
        $d = $b->getDefender();
        $aRoll = $dR->roll($a->getNumDice());
        $b->addCombatRoll($aRoll,$a);
        $dRoll = $dR->roll($d->getNumDice());
        $b->addCombatRoll($dRoll,$d);
        $em->flush();
    }

    public function rollLeaderDice(Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        $dR = $this->get('meldon.dice');
        $b = $game->getBattle();
        $a = $b->getAttacker();
        $d = $b->getDefender();
        $aRoll = $dR->roll($a->getRerollDice());
        $b->addReroll($aRoll,$a);
        $dRoll = $dR->roll($d->getRerollDice());
        $b->addReroll($dRoll,$d);
        $em->flush();
    }
    /**
     * @Route("/wotr/map/{id}", name="show_map")
     * @ParamConverter("post", class="WotRBundle:Game", options={"repository_method" = "getCurrentGame"})
     * @param Game $game
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function mapAction(Game $game, Request $request)
    {
        return $this->render('WotRBundle:Default:map.html.twig',
            array('game'=> $game));
    }

    /**
     * @Route("/wotr/ajax/regions/{id}")
     * @ParamConverter("post", class="WotRBundle:Game", options={"repository_method" = "getCurrentGame"})
     * @param Game $game
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function ajaxRegionsAction(Game $game, Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            $locs = $game->getLocations();
            $outputArr = array();
            /** @var Location $l */
            foreach ($locs as $l) {
                $outputArr[$l->getID()] = array(
                    'coords' => $l->getCoords(),
                    'name' => $l->getName(),
                    'nation' => $l->getNationDetails() ? $l->getNationDetails()->getId() : 0,
                    'town' => $l->getTown(),
                    'city' => $l->getCity(),
                    'stronghold' => $l->getStronghold(),
                    'unitPos' => $l->getUnitPos()
                );
            }
            $response = new JsonResponse();
            $response->setData(array('data' => $outputArr));
            return $response;
        }
        return new Response('This is not ajax!', 400);
    }

    /**
     * @Route("/wotr/ajax/units/{id}")
     * @ParamConverter("post", class="WotRBundle:Game", options={"repository_method" = "getCurrentGame"})
     * @param Game $game
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function ajaxUnitsAction(Game $game, Request $request)
    {

        if ($request->isXMLHttpRequest()) {
            $units = $game->getUnits();
            $outputArr = array();
            /** @var Unit $u */
            foreach ($units as $u) {
                if ( $u->getLocation() ) {
                    $outputArr[$u->getId()] = array(
                        'unitName' => $u->getName(),
                        'loc' => $u->getLocation()->getId(),
//                        'type' => $u->getType()->getName(),
//                        'nation' => $u->getNation()->getDetails()->getId(),
                        'sideId' => $u->getSide()->getId()
                    );
                }
            }
            $response = new JsonResponse();
            $response->setData(array('data' => $outputArr));
            return $response;
        }
        return new Response('This is not ajax!', 400);
        $unitTable = 'units';
        $request = new Request();
        switch( $request->getProperty('request') ) {
            case 'units':
                $sql = "SELECT {$unitTable}.id, loc, unit.name as unitName, unitType.name as type, "
                    . "nation.side_id as sideId, nation.name as nation FROM {$unitTable} "
                    . "LEFT JOIN unit ON unit_id = unit.id "
                    . "LEFT JOIN nation ON nation_id = nation.id "
                    . "LEFT JOIN unitType on unitType_id = unitType.id "
                    . "WHERE loc != 0 "
                    . "ORDER BY {$unitTable}.loc";
                $unitsQ = DBP::query( $sql );
                $outputArr = array();
                foreach( $unitsQ->rows as $unit ) {
                    $outputArr[$unit->id] = array(
                        'unitName' => $unit->unitName,
                        'loc' => $unit->loc,
                        'type' => $unit->type,
                        'nation' => $unit->nation,
                        'sideId' => $unit->sideId
                    );
                }
                echo json_encode($outputArr);
                break;
            case 'recruitable':
                $sql = "SELECT {$unitTable}.id, IF( ISNULL(unit.name),CONCAT(nation.name, ' ', unitType.name, ' (', COUNT({$unitTable}.id), ' remaining)'),unit.name) AS unitName "
                    . "FROM {$unitTable} "
                    . "LEFT JOIN unit ON {$unitTable}.unit_id = unit.id "
                    . "LEFT JOIN nation ON nation.id = nation_id "
                    . "LEFT JOIN unitType ON unitType.id = unitType_id "
                    . "WHERE loc = 0 AND ( side_id = 2 || casualty != 1 ) AND unit.unitType_id <= 4 "
                    . "GROUP BY CONCAT(nation.name, ' ', unitType.name) "
                    . "UNION ALL "
                    . "SELECT {$unitTable}.id, unit.name AS unitName FROM {$unitTable} "
                    . "LEFT JOIN unit ON {$unitTable}.unit_id = unit.id "
                    . "LEFT JOIN nation ON nation.id = nation_id "
                    . "LEFT JOIN unitType ON unitType.id = unitType_id "
                    . "WHERE loc = 0 AND casualty != 1 AND unit.unitType_id >= 5 "
                    . "ORDER BY id";
                $units = DBP::query($sql,NULL,NULL,'keyPair');
                echo json_encode($units->rows);
                break;
            case 'recruit':
                $unitID = $request->getProperty('unit');
                $regionID = $request->getProperty('region');
                if ( $regionID && $unitID ) {
                    $sql = "UPDATE {$unitTable} SET loc = ? WHERE id = ?";
                    $pA = array($regionID, $unitID);
                    DBP::query($sql, $pA);
                    echo "Unit recruited.";
                } else {
                    echo "Please select a unit to recruit.";
                };
                break;
            case 'move':
                $unitIDs = $request->getProperty('selectedUnits');
                $dest = $request->getProperty('dest');
                if ( is_array($unitIDs) ) {
                    $qMarks = str_repeat('?,', count($unitIDs) - 1) . '?';
                    $sql = "UPDATE {$unitTable} SET loc = ? WHERE id IN ($qMarks)";
                    array_unshift( $unitIDs, $dest );
                    DBP::query($sql, $unitIDs);
                    echo "Units moved";
                } else {
                    echo "Please select units to move";
                }
                break;
            case 'remove':
                $unitIDs = $request->getProperty('selectedUnits');
                $casualty = $request->getProperty('casualty');
                if ( is_array($unitIDs) ) {
                    $qMarks = str_repeat('?,', count($unitIDs) - 1) . '?';
                    $sql = "UPDATE {$unitTable} SET loc = 0, casualty = ? WHERE id IN ($qMarks)";
                    array_unshift( $unitIDs, $casualty );
                    DBP::query($sql, $unitIDs);
                    echo "Units removed";
                } else {
                    echo "Please select units to remove";
                }
                break;
            case 'reduce':
                $unitIDs = $request->getProperty('selectedUnits');
                $regionID = $request->getProperty('region');
                $return = '';
                foreach( $unitIDs as $unitID ) {
                    $sql = "SELECT nation_id FROM {$unitTable} "
                        . "LEFT JOIN unit ON {$unitTable}.unit_id = unit.id "
                        . "WHERE {$unitTable}.id = ?";
                    $result = DBP::query($sql,array($unitID));
                    $nation = $result->nation_id;

                    $sql = "SELECT {$unitTable}.id, casualty FROM {$unitTable} "
                        . "LEFT JOIN unit ON {$unitTable}.unit_id = unit.id "
                        . "WHERE nation_id = ? AND loc = 0 AND unitType_id = 1 "
                        . "ORDER BY casualty DESC";
                    $regulars = DBP::query($sql, array($nation));
                    if ( $regulars->getRows() ) {
                        $sql = "UPDATE {$unitTable} SET loc = 0, casualty = 1 WHERE id = ?";
                        $pA = array($unitID);
                        DBP::query($sql,$pA);
                        $sql = "UPDATE {$unitTable} SET loc = ?, casualty = 0 WHERE id = ?";
                        $pA = array($regionID, $regulars->id);
                        DBP::query($sql,$pA);
                        $return = 'Elite reduced';
                    } else {
                        $return = 'No regular to reduce elite';
                    }

                }
                echo $return;
        }
    }
    /**
     * @Route("/wotr/create/{id}")
     * @param Scenario $scenario
     * @return Response
     */
    public function createGameAction(Scenario $scenario)
    {
        // Get the entity manager
        $em = $this->getDoctrine()->getManager();
        // Create new game
        $game = new Game();
        $em->persist($game);
        // Attach the scenario
        $game->setScenario($scenario);
        $as = new ActionStack();
        $as->setGame($game);
        $log = new Log();
        $log->setGame($game);
        $game->setPhase($em->getRepository('WotRBundle:Phase')->find(1));
        // Set up the starting nations
        $startNations = $em->getRepository('WotRBundle:NationDetails')
            ->findAll();
        foreach( $startNations as $sN ) {
            new Nation($sN,$game);
        }
        $em->flush();
        $fs = new Fellowship();
        // Set up the board
        $startLocs = $em->getRepository('WotRBundle:LocationDetails')
                ->findAll();
        // Add locations - saving the FS Location to be added later
        foreach( $startLocs as $sL )
        {
            $location = new Location($sL,$game);
            if ( $sL == $scenario->getFSStart() )
            {
                $FSLoc = $location;
                $fs->setLocation($FSLoc);
            }
        }
        $em->flush();
        // Can't add connections until all locations initialises
        /** @var Location $sL */
        foreach( $startLocs as $sL )
        {
            /** @var ConnectionDetails $cD */
            foreach( $sL->getConnections() as $cD )
            {
                $c = new Connection();
                $source = $game->getLocations()->filter(
                    function($l) use ($cD)
                        {
                            return $l->getDetails() == $cD->getSource();
                        })->first();
                $dest = $game->getLocations()->filter(
                        function($l) use ($cD)
                        {
                            return $l->getDetails() == $cD->getDestination();
                        })->first();
                $c->setSource($source);
                $c->setDestination($dest);
                $c->setStronghold($cD->getStronghold());
            }
        }
        // Set up the fellowship and location
        $fs->setCorruption($scenario->getCorruption());
        $em->flush();
        // Set up the characters and add guide to fellowship. 
        $startChars = $em->getRepository('WotRBundle:CharacterDetails')
                ->getStartCharacters($scenario);
        $charUnitType = $em->getRepository('WotRBundle:UnitType')
            ->findOneById(4);
        /* @var $sC CharacterDetails */
        foreach( $startChars as $sC ) {
            $cN = $game->getNations()->filter(
                function ($n) use ($sC) {
                    return $sC->getNationDetails()->first() && $n->getDetails()->getId() == $sC->getNationDetails()->first()->getId();
                }
            )->first();
            if ( !$cN ) {
                $cN = NULL;
            }
            if ( $sC->getStartloc() ) {
                $cL = $game->getLocations()->filter(
                    function ($l) use ($sC) {
                        return $l->getDetails() == $sC->getStartloc();
                    }
                )->first();
            } else {
                $cL = NULL;
            }
            $c = new Character($cN,$charUnitType,$cL,$game,$sC);
            if ( $sC->getStartInFellowship() )
            {
                $c->setFellowship($fs);
            }
            if ( $sC == $scenario->getStartGuide() )
            {
                $startGuide = $c;
                $fs->setGuide($startGuide);
            }
        }
        // Add fellowship to game
        $fs->setGame($game);
        $em->flush();
        // Set up the rings and add to game
        $sides = $em->getRepository('WotRBundle:Side')
                ->getPlayedSides();
        foreach( $sides as $side )
        {
            $eR = new ElvenRings($side,$scenario->getStartRings($side), $game);
            $player = new Player($em->getRepository('UserBundle:User')->find(3), $side, $game);
        }
        // Set up the card decks and hunt tiles
        $cardGroupDetails = $em->getRepository('WotRBundle:CardGroupDetails')
                ->findAll();
        
        /* @var $cGD CardGroupDetails */
        foreach( $cardGroupDetails as $cGD )
        {
            $cG = new CardGroup($cGD,$game);
        }
        $em->flush();
        // Add cards to groups
        $cardEntryDetails = $em->getRepository('WotRBundle:CardEntryDetails')
                ->getStartCards($scenario);
        
        /* @var $cED CardEntryDetails */
        foreach( $cardEntryDetails as $cED )
        {
            if ( $cED->getTurn() == 1 )
            {
                $cG = $game->getCardGroupByID($cED->getStartGroup()->getId());                
                for ( $i = 1; $i <= $cED->getCopies(); $i++ )
                {
                    $card = new Card($cED->getCardDetails());
                    $cG->addToBottom($card);
                }
            }
        }
        
        // Shuffle groups
        $cGs = $game->getCardGroups();
        foreach( $cGs as $cG )
        {
            $cG->shuffle();
        }
        $em->flush();
        // Set up units
        $startUnits = $em->getRepository('WotRBundle:UnitEntryDetails')
                ->getStartUnits($scenario);
        
        $locations = $game->getLocations();
        /* @var $sU UnitEntryDetails */
        foreach( $startUnits as $sU ) {
            $startLocDetail = $sU->getStartLoc();
            $startLoc = $locations->filter(
                    /* @var $entry Location */
                    function($entry) use ($startLocDetail)
                    {
                        return $entry->getDetails() == $startLocDetail;
                    }
                )->first();
            if ( $startLoc === false ) $startLoc = NULL;
            $unitNationDetail = $sU->getNationDetails();
            $unitNation = $game->getNations()->filter(
                    /* @var $entry Nation */
                    function($entry) use ($unitNationDetail)
                    {
                        return $entry->getDetails() == $unitNationDetail;
                    }
                )->first();
            for ( $i = 0; $i < $sU->getNumber(); $i++ )
            {
                $unit = new Unit($unitNation,$sU->getUnitType(),$startLoc,$game);
            }
            
        }
        $game->setCurrentSide($game->getSide('FP'));
        try {
            $em->flush();
        } catch (\Exception $e) {
            echo "<pre>Error: {$e}</pre>";
        }
        return $this->redirect($this->generateUrl('meldon_wotr_wotr_index',
            array('id'=>$game->getID())));
    }
}

//        Code to setup nations

//        
//        $em->flush();    

//        $stmt = $this->getDoctrine()->getEntityManager('wotr')
//                ->getConnection()
//                ->prepare('SELECT * FROM hunttile2 WHERE id != 1');
//        $stmt->execute();
//        $result = $stmt->fetchAll();
//        
//        foreach( $result as $tile ) {
//        
//            $newTile = new \Meldon\WotRBundle\Entity\HuntTile();
//
//            $em->persist($newTile);
//
//            $newTile->setName($tile['name']);
//            $newTile->setCopies($tile['copies']);
//            $newTile->setCorruption($tile['corruption']);
//            $newTile->setReveal($tile['reveal']==1);
//            $newTile->setStop($tile['stop']==1);
//            $newTile->setSpecial($tile['special']==1);
//            $newTile->setTurn($tile['turn']);
//            $newTile->setFilename($tile['filename']);
//            switch( $tile['side_id'] )
//            {
//                case 0:
//                    $side = 3;
//                    break;
//                case 1:
//                    $side = 2;
//                    break;
//                case 2:
//                    $side = 1;
//                    break;
//            }
//            $newTile->setSide($em->getRepository('WotRBundle:Side')
//                    ->find($side));
//        
//        }
//        
//        
//        $em->flush();

//      $stmt = $this->getDoctrine()->getEntityManager('wotr')
//                ->getConnection()
//                ->prepare('SELECT * FROM hunttile2 ORDER BY id');
//        $stmt->execute();
//        $result = $stmt->fetchAll();
//        
//        $nation = $em->getRepository('WotRBundle:HuntTile')
//                ->findAll();
//        
//        foreach( $result as $tile ) {
//        
//            $newTile = new \Meldon\WotRBundle\Entity\CardEntryDetails();
//
//            $em->persist($newTile);
//
//            $newTile->setScenario($scenario);
//            $newTile->setCopies($tile['copies']);
//            $newTile->setTurn($tile['turn']);
//            
//        foreach( $nation as $tileDetail )
//        {
//            if ( $tileDetail->getName() === $tile['name'] )
//            {
//                $newTile->setCardDetails($tileDetail);
//            }
//        }
//        
//        }
//        
//        
//        $em->flush();
//         
//         
//         
//         
//        Add cards - might be all that is needed to add all cards!!!
//        
//        $nation = $em->getRepository('WotRBundle:CardEntryDetails')
//                ->findBy(array('turn'=>1));
//        
//        $position = 1;
//        
//        /* @var $cE CardEntryDetails */
//        foreach( $nation as $cE ) {
//            for( $i = 0; $i < $cE->getCopies(); $i++ )
//            {
//                $card = new \Meldon\WotRBundle\Entity\Card();
//                $card->setPosition($position++);
//                $card->setCardDetails($cE->getCardDetails());
//                $card->setGroup($cE->getStartGroup());
//                $em->persist($card);
//            }    
//        }
//        
//        $em->flush();

//        
//        
//        /* @var $card \Meldon\WotRBundle\Entity\EventCard */
//        foreach( $eventCards as $card )
//        {
//            $entryDetail = new CardEntryDetails();
//            
//            $em->persist( $entryDetail );
//            
//            $entryDetail->setCopies(1);
//            $entryDetail->setScenario($scenario);
//            $entryDetail->setTurn(1);
//            $entryDetail->setCardDetails($card);
//            
//            if ( $card->getSide()->getId() == 1 )
//            {
//                if ( $card->getType()->getId() == 1 )
//                {
//                    $entryDetail->setStartGroup($em->getRepository('WotRBundle:CardGroupDetails')
//                ->find(7));
//                } else {
//                    $entryDetail->setStartGroup($em->getRepository('WotRBundle:CardGroupDetails')
//                ->find(8));
//                }
//            } else 
//            {
//                if ( $card->getType()->getId() == 1 )
//                {
//                    $entryDetail->setStartGroup($em->getRepository('WotRBundle:CardGroupDetails')
//                ->find(9));
//                } else {
//                    $entryDetail->setStartGroup($em->getRepository('WotRBundle:CardGroupDetails')
//                ->find(10));
//                }
//            }
//            
//        }
        
//       
//        $game->enterMordor();
//        $stmt = $this->getDoctrine()->getEntityManager('wotr')
//                ->getConnection()
//                ->prepare('SELECT * FROM (SELECT * FROM sstratdeck WHERE scenario_id & 2 
//UNION
//SELECT * FROM fpstratdeck WHERE scenario_id & 2
//UNION
//SELECT * FROM fpchardeck WHERE scenario_id & 2
//UNION
//SELECT * FROM schardeck WHERE scenario_id & 2) as tmp');
//        $stmt->execute();
//        $result = $stmt->fetchAll();
//        
//        
//        
//        foreach( $result as $card ) {
//        
//            /* @var $eventCard \Meldon\WotRBundle\Entity\EventCard */
//            $eventCard = $em->getRepository('WotRBundle:EventCard')
//                    ->findOneByName($card['name']);
//            
//            $eventCard->setBattleEvent($em->getRepository('WotRBundle:BattleEvent')
//                    ->findOneByName($card['bname']));
//
//        }