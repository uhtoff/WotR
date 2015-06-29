<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 19/05/2015
 * Time: 21:15
 */

namespace Meldon\WotRBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class LogItem extends Event
{
    const MOVEFS = 2;
    protected $action;
    protected $post;
    public function recordAction($action,$text)
    {
        $this->action = $action;
        $this->post = $text;
    }
}