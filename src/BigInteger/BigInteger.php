<?php

namespace BigInteger;

class BigInteger implements BigIntegerInterface
{
    const WORD_LENGTH = 9;
    const NEXT_RANK = 1000000000;
    const SIGN_POSITIVE = 1;
    const SIGN_NEGATIVE = -1;
    
    private $value;
    private $unsignedValue;
    private $sign = self::SIGN_POSITIVE;
    
    public function __construct($value)
    {
        if($value{0} == '+' || $value{0} == '-') {
            $this->unsignedValue = substr($value, 1);
            
            $this->sign = $value{0} == '-' ? self::SIGN_NEGATIVE : self::SIGN_POSITIVE;
        } else {
            $this->unsignedValue = $value;
        }
        
        $this->value = $value;
    }
    
    public function add(BigInteger $integer)
    {
        if ($integer->getSign() == $this->getSign()) {
            $unsignedValue = $this->addUnsigned($this->unsignedValue, $integer->getUnsignedValue());
            $signString = $this->getSign() == self::SIGN_NEGATIVE ? '-' : '';
            
            return new BigInteger($signString . $unsignedValue);
        } else {
            $result = $this->getSign() == self::SIGN_NEGATIVE ?
                $this->subUnsigned($integer->getUnsignedValue(), $this->getUnsignedValue()) :
                $this->subUnsigned($this->getUnsignedValue(), $integer->getUnsignedValue());
                
            return new BigInteger($result);
        }
    }
    
    private function addUnsigned($int1, $int2)
    {
        $int1Parts = $this->splitToWords($int1);
        $int2Parts = $this->splitToWords($int2);
        $int1Count = count($int1Parts);
        $int2Count = count($int2Parts);
        $max = max($int1Count, $int2Count);
        
        $result = array();
        
        $int1Parts = array_pad($int1Parts, -$max, 0);
        $int2Parts = array_pad($int2Parts, -$max, 0);
        
        $higherDigit = 0;
        
        for ($i=$max-1; $i >= 0; $i--) {
            $sum = $int1Parts[$i] + $int2Parts[$i] + $higherDigit;
            $length = strlen($sum);
            if ($length > self::WORD_LENGTH) {
                array_unshift($result, substr($sum, -self::WORD_LENGTH, self::WORD_LENGTH));
                $higherDigit = substr($sum, 0, -self::WORD_LENGTH);
            } else {
                array_unshift($result, $sum);
                $higherDigit = 0;
            }
        }
        
        if ($higherDigit > 0) {
            array_unshift($result, $higherDigit);
        }
        
        return implode('', $result);
    }
    
    private function splitToWords($integer)
    {
        $result = array();
        
        $length   = strlen($integer);
        $count    = ceil($length/self::WORD_LENGTH);
        
        for ($i = 0; $i < $count; $i++) {
            array_unshift($result, substr($integer, -($i+1)*self::WORD_LENGTH, $i==0?self::WORD_LENGTH:-$i*self::WORD_LENGTH));
        }
        
        return $result;
    }
    
    public function sub(BigInteger $integer)
    {
        throw new \Exception('Not implemented');
    }
    
    private function subUnsigned($int1, $int2)
    {
        if ($int1 == $int2) {
            return '0';
        }
        
        if ($int1 < $int2) {
            return '-' . $this->subUnsigned($int2, $int1);
        }
        
        $int1Parts = $this->splitToWords($int1);
        $int2Parts = $this->splitToWords($int2);
        $int1Count = count($int1Parts);
        $int2Count = count($int2Parts);
        $max = max($int1Count, $int2Count);
        
        $result = array();
        
        $int1Parts = array_pad($int1Parts, -$max, 0);
        $int2Parts = array_pad($int2Parts, -$max, 0);
        
        $higherDigit = 0;
        
        for ($i=$max-1; $i >= 0; $i--) {
            if ($int1Parts[$i] >= $int2Parts[$i] + $higherDigit) {
                $diff = $int1Parts[$i] - $int2Parts[$i] - $higherDigit;
                $higherDigit = 0;
            } else {
                $diff = self::NEXT_RANK + $int1Parts[$i] - $int2Parts[$i] - $higherDigit;
                $higherDigit = 1;
            }
            
            array_unshift($result, $diff);
        }
        
        for ($i = 0; $i < $max; $i++) {
            if ($result[$i] == 0) {
                array_shift($result);
            } else {
                break;
            }
        }
        
        return implode('', $result);
        
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function getUnsignedValue()
    {
        return $this->unsignedValue;
    }
    
    public function getSign()
    {
        return $this->sign;
    }
    
}