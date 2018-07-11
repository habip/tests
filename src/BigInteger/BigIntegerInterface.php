<?php

namespace BigInteger;

interface BigIntegerInterface
{
    /**
     * Adds $integer to $this and returns new BigInteger as result
     * @param BigInteger $integer
     * @return BigInteger
     */
    public function add(BigInteger $integer);
    
    /**
     * Returns string representation of this big integer
     * @return string
     */
    public function getValue();
    
}
