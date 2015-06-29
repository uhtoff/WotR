<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 24/06/2015
 * Time: 09:12
 */

namespace Meldon\WotRBundle\Tests\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Meldon\WotRBundle\Collection\GameCollection;

class GameCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GameCollection
     */
    protected $stub;

    /**
     * Setup an array to test on and generate a stub so that the array is return by GetElements
     */
    protected function setUp()
    {
        $elements = new ArrayCollection();
        for( $i = 10; $i > 0; $i-- ){
            $elements->add($i);
        }
        $this->stub = new GameCollection($elements);
    }

    public function testAdd()
    {
        $this->assertTrue($this->stub->add(11));
        $this->assertContains(11,$this->stub->getElements());
        $this->assertCount(11,$this->stub->getElements());
    }
    public function testClear()
    {
        $this->assertContains(5,$this->stub->getElements());
        $this->stub->clear();
        $this->assertNotContains(5,$this->stub->getElements());
        $this->assertCount(0,$this->stub->getElements());
    }
    public function testContains()
    {
        $this->assertTrue($this->stub->contains(5));
        $this->assertFalse($this->stub->contains(11));
    }
    public function testIsEmpty()
    {
        $this->assertFalse($this->stub->isEmpty());
        $this->stub->clear();
        $this->assertTrue($this->stub->isEmpty());

    }
    public function testRemove()
    {
        $this->assertSame(10,$this->stub->remove(0));
        $this->assertCount(9,$this->stub->getElements());
        $this->assertNull($this->stub->remove(100));
        $this->assertCount(9,$this->stub->getElements());
    }
    public function testRemoveElement()
    {
        $this->assertContains(5,$this->stub->getElements());
        $this->assertTrue($this->stub->removeElement(5));
        $this->assertNotContains(5,$this->stub->getElements());
        $this->assertCount(9,$this->stub->getElements());
        $this->assertFalse($this->stub->removeElement(100));
        $this->assertCount(9,$this->stub->getElements());
    }
    public function testContainsKey()
    {
        $this->assertTrue($this->stub->containsKey(4));
        $this->assertFalse($this->stub->containsKey('15'));
    }
    public function testGet()
    {
        $this->assertSame(10,$this->stub->get(0));
        $this->assertNotSame(9,$this->stub->get(2));
        $this->assertNull($this->stub->get('banana'));
    }
    public function testGetKeys()
    {
        $this->assertInternalType('array',$this->stub->getKeys());
        $key = range(0,9);
        $this->assertSame($key,$this->stub->getKeys());
    }
    public function testGetValues()
    {
        $this->assertInternalType('array',$this->stub->getValues());
        $key = range(10,1,-1);
        $this->assertSame($key,$this->stub->getValues());
    }
    public function testSet()
    {
        $this->stub->set(5,13);
        $this->stub->set('banana','yellow');
        $this->assertContains(13,$this->stub->getElements());
        $this->assertContains('yellow',$this->stub->getElements());
        $this->assertNotContains(5,$this->stub->getElements());
        $this->assertArrayHasKey('banana',$this->stub->getElements());
    }
    public function testToArray()
    {
        $array = $this->stub->toArray();
        $this->assertInternalType('array',$array);
        $key = range(10,1,-1);
        $this->assertSame($key,$array);
        $this->assertNotInstanceOf('Doctrine\Common\Collections\ArrayCollection',$array);
    }
    public function testCurrent()
    {
        $this->assertSame(10,$this->stub->current());
    }
    public function testKey()
    {
        $this->assertSame(0,$this->stub->key());
    }
    public function testNext()
    {
        $this->assertSame(9,$this->stub->next());
    }
    public function testFirst()
    {
        $this->stub->next();
        $this->assertSame(10,$this->stub->first());
    }
    public function testLast()
    {
        $this->assertSame(1,$this->stub->last());
    }
    public function testExists()
    {
        $this->assertTrue($this->stub->exists(
            function($e) {
                return $e < 2;
            }));
        $this->assertFalse($this->stub->exists(
            function($e) {
                return $e > 12;
            }));
    }
    public function testForAll()
    {
        $this->assertFalse($this->stub->forAll(
            function($e) {
                return $e < 2;
            })
        );
        $this->assertTrue($this->stub->forAll(
            function($e) {
                return $e < 12;
            })
        );
    }
    public function testMap()
    {
        $mapped = $this->stub->map(
            function($e) {
                return $e + 1;
            }
        );
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection',$mapped);
        $result = range(11,2,-1);
        $this->assertSame($result,$mapped->toArray());
    }
    public function testPartition()
    {
        $partitioned = $this->stub->partition(
            function($e) {
                return $e%2==0;
            }
        );
        $this->assertInternalType('array',$partitioned);
        $this->assertCount(2,$partitioned);
        $keys = range(0,8,2);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection',$partitioned[0]);
        $this->assertSame($keys,$partitioned[0]->getKeys());
        $keys = array_map(function($v){return $v+1;},$keys);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection',$partitioned[1]);
        $this->assertSame($keys,$partitioned[1]->getKeys());
    }
    public function testIndexOf()
    {
        $this->assertSame(1,$this->stub->indexOf(9));
        $this->assertFalse($this->stub->indexOf('9'));
    }
    public function testSlice()
    {
        $this->assertInternalType('array',$this->stub->slice(4));
        $this->assertCount(4,$this->stub->slice(0,4));
        $this->assertCount(2,$this->stub->slice(8));
        $result = array(
            8 => 2,
            9 => 1
        );
        $this->assertSame($result,$this->stub->slice(8));
    }
    public function testGetIterator()
    {
        $this->assertInstanceOf('ArrayIterator',$this->stub->getIterator());
    }
    public function testOffsetExists()
    {
        $this->assertInternalType('boolean',$this->stub->offsetExists(5));
        $this->assertTrue($this->stub->offsetExists(5));
        $this->assertFalse($this->stub->offsetExists(15));
    }
    public function testOffsetGet()
    {
        $this->assertSame(5,$this->stub->offsetGet(5));
        $this->assertNull($this->stub->offsetGet(15));
    }
    public function testOffsetSet()
    {
        $this->stub->offsetSet(4,100);
        $this->assertContains(100,$this->stub->getElements());
        $this->assertSame(100,$this->stub->offsetGet(4));
    }
    public function testOffsetUnset()
    {
        $this->stub->offsetUnset(5);
        $this->assertCount(9,$this->stub->getElements());
        $this->assertNotContains(5,$this->stub->getElements());
    }
    public function testCount()
    {
        $this->assertSame(10,$this->stub->count());
        $this->stub->set('banana','yellow');
        $this->assertSame(11,$this->stub->count());
    }
    public function testFilter()
    {
        $filtered = $this->stub->filter(
            function($e) {
                return $e%2==0;
            }
        );

        $this->assertInstanceOf('Meldon\WotRBundle\Collection\GameCollection',$filtered);
        $this->assertCount(5,$filtered);
        $results = array_combine(range(0,8,2),range(10,2,-2));
        $this->assertSame($results,$filtered->toArray());
    }
    public function testMatching()
    {
        $elements = new ArrayCollection();
        $elements->add(array('id'=>40));
        $elements->add(array('name'=>'Rob'));
        $elements->add(array('id'=>45));
        $this->stub = new GameCollection($elements);
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where($expr->gt('id',35));
        $matched = $this->stub->matching($criteria);
        $this->assertInstanceOf('Meldon\WotRBundle\Collection\GameCollection',$matched);
        $this->assertCount(2,$matched);
        $results = array(
            0 => array('id'=>40),
            2 => array('id'=>45)
        );
        $this->assertSame($results,$matched->toArray());
    }

}
