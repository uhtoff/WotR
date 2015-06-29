<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 19/05/2015
 * Time: 23:24
 */

namespace Meldon\WotRBundle\Factory;

use Meldon\WotRBundle\Entity\Game;
use Meldon\WotRBundle\Entity\Log;
use Meldon\WotRBundle\Entity\LogItem;
use Meldon\WotRBundle\Entity\Action;
use Meldon\WotRBundle\Entity\ActionDieDetails;
use Meldon\WotRBundle\Entity\Decision;


class LogFactory {
    /**
     * @var LogItem
     */
    private static $logItem;
    public static function startLog(Game $game, Decision $decision = NULL)
    {
        self::$logItem = new LogItem();
        self::setGameDetails($game);
    }
    public static function setGameDetails(Game $game)
    {
        self::$logItem->setTurn($game->getTurn());
        self::$logItem->setPhase($game->getPhase()->getName());
        if ( $game->getCurrentAction() ) {
            $action = $game->getCurrentAction();
            self::$logItem->setActionDetails($action->getDetails());
            if ( $action->getActionDie() ) {
                self::$logItem->setActionDie($action->getActionDie()->getDetails());
            }
        }
    }
    public static function setAction(ActionDetails $action)
    {
        self::$logItem->setActionDetails($action);
    }
    public static function setText($text)
    {
        /** @var string $text */
        self::$logItem->setText($text);
    }
    public static function getText()
    {
        return self::$logItem->getText();
    }
    public static function setDie(ActionDieDetails $aD)
    {
        self::$logItem->setActionDie($aD);
    }
    public static function completeLog(Log $log)
    {
        if ( self::$logItem->getText() ) {
            self::$logItem->setCreatedAt(new \DateTime());
            self::$logItem->setLog($log);
        }
    }

}