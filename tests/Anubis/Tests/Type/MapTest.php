<?php

namespace Anubis\Tests\Type;

use Anubis\Type\Map;

/**
 * Test for the Map
 *
 * @author Amir Abdou <amisaad@gmail.com>
 */
class MapTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayAccess()
    {
        $key = new \DateTime('now');
        $map = new Map();
        $map['key1'] = 'value1';
        $map[1.0]    = 'value2';
        $map[true]   = false;
        $map[$key]  = [1, 2];
        
        $this->assertSame('value1', $map['key1']);
        $this->assertSame('value2', $map[1.0]);
        $this->assertFalse($map[true]);
        $this->assertSame([1, 2], $map[$key]);
    }
}