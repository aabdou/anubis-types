<?php

namespace Anubis\Type;

/**
 * Map
 *
 * @author Amir Abdou <amisaad@gmail.com>
 */
class Map implements \ArrayAccess
{
    /**
     * Internal list of elements
     *
     * @var array
     */
    private $elements = [];
    
    /**
     * Set a value at the offset
     *
     * @param mixed $offset Key
     * @param mixed $value  Value
     *
     * @throws \RuntimeException
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            throw new \RuntimeException('The offset cannot be null');
        }
        
        // Create the map entry
        $element = new \stdClass;
        $element->key   = $offset;
        $element->value = $value;
        
        // Hash the key and store it in the list
        $hash = $this->hashKey($offset);
        $this->elements[$hash] = $element;        
    }
    
    /**
     * Return true if the offset is set in the list
     *
     * @param mixed $offset Key
     *
     * @return boolean
     */    
    public function offsetExists($offset)
    {
        $hash = $this->hashKey($offset);
        
        return isset($this->elements[$hash]);
    }
    
    /**
     * Unset the item at the specifiec key
     *
     * @param mixed $offset Key
     */
    public function offsetUnset($offset)
    {
        $hash = $this->hashKey($offset);
        
        unset($this->elements[$hash]);
    }
    
    /**
     * Return the value at the given key, or null if there is none
     *
     * @param mixed $offset Key
     *
     * @return mixed
     */     
    public function offsetGet($offset)
    {
        $hash = $this->hashKey($offset);
        
        return isset($this->elements[$hash]) ? $this->elements[$hash]->value : null;
    }
    
    /**
     * Apply a hash function to the key
     * 
     * @param mixed $key Key
     * 
     * @return string
     */
    private function hashKey($key)
    {
        $hash = '';
        if (is_scalar($key)) {
            $hash = sha1(gettype($key).$key);
        } else {
            $hash = spl_object_hash($key);
        }
        
        return $hash;
    }
}