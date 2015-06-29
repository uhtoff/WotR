<?php
namespace Meldon\DiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DiceController extends Controller
{
    private $sides = 6;
    private $total = 0;
    private $results = array();
	public function roll( $number, $sides = NULL, $order = NULL ) {
		$this->total = 0;
        $this->results = array();
        if ( !$sides )
        {
            $sides = $this->sides;
        }
		for ( $i = 1; $i <= $number; $i++ ) {
			$this->total += $this->results[$i] = mt_rand( 1, $sides );
		}
		return $this->getResults($order);
    }
    public function getTotal()
    {
        return $this->total;
    }
    public function getResults($order = NULL)
    {
        if ( $order == "ASC" ) {
			sort( $this->results );
		} elseif ( $order == "DESC" ) {
			rsort( $this->results );
		}
        return $this->results;
    }
}
