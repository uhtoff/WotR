<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 17/06/2015
 * Time: 10:29
 */

namespace Meldon\WotRBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Meldon\WotRBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;

abstract class WotRType extends AbstractType {
    protected $game;
    protected $em;
    public function __construct(Game $game, EntityManager $em)
    {
        $this->game = $game;
        $this->em = $em;
    }
}