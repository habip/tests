<?php

use BigInteger\BigInteger;

class BigIntegerTest extends PHPUnit_Framework_TestCase
{
    
    public function testBigIntegerAdd()
    {
        $integer1 = new BigInteger('99999999999999999');
        $integer2 = new BigInteger('99999999999999999');
        
        $result = $integer1->add($integer2);
        
        $this->assertEquals('199999999999999998', $result->getValue());
        
        $integer1 = new BigInteger('99999999');
        $integer2 = new BigInteger('99999999999999999');
        
        $result = $integer1->add($integer2);
        
        $this->assertEquals('100000000099999998', $result->getValue());
    }
    
    public function testBigIntegerAddNegative()
    {
        $integer1 = new BigInteger('-99999999');
        $integer2 = new BigInteger('99999999999999999');
        
        $result = $integer1->add($integer2);
        
        $this->assertEquals('99999999900000000', $result->getValue());
        
        $integer1 = new BigInteger('99999999');
        $integer2 = new BigInteger('-99999999999999999');
        
        $result = $integer1->add($integer2);
        
        $this->assertEquals('-99999999900000000', $result->getValue());

        $integer1 = new BigInteger('99999999999999999');
        $integer2 = new BigInteger('-99999999999999999');
        
        $result = $integer1->add($integer2);
        
        $this->assertEquals('0', $result->getValue());
    }
}