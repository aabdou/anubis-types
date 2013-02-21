<?php

namespace Anubis\Type;

/**
 * Map
 *
 * @author Amir Abdou <amisaad@gmail.com>
 */
class Map implements \ArrayAccess, \Iterator, \Countable
{
    /**
     * Internal list of elements
     *
     * @var array
     */
    private $elements = [];
    
    /**
     * Internal pointer
     * 
     * @var int 
     */
    private $index = 0;
    
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
        if (is_null($offset) || is_resource($offset) || is_array($offset)) {
            throw new \RuntimeException('Offset cannot be null, resource or an array');
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

    /**
     * Return the number of elements in the map
     * 
     * @return int
     */
    public function count ()
    {
        return count($this->elements);
    }
    
    /**
     * Return the key of the current element
     * 
     * @return mixed
     */
    public function current()
    {   
        return current($this->elements)->key;
    }
    
    /**
     * Return the hash of the current element
     * 
     * @return string
     */
    public function key()
    {        
        return key($this->elements);
    }

    /**
     * Advance to the next element
     */
    public function next()
    {
        ++$this->index;
        next($this->elements);
    }

    /**
     * Reset the internal pointer
     */
    public function rewind()
    {
        $this->index = 0;
        reset($this->elements);
    }

    /**
     * Return true if the next position is valid
     * 
     * @return boolean
     */
    public function valid()
    {
        return count($this->elements) != 0 && $this->index < count($this->elements);
    }
}