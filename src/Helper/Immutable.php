<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 11.06.18
 * Time: 17:32
 */

namespace Brace\Core\Helper;


class Immutable
{


    public function __construct(
        private array $__immutableData
    ){}

    /**
     * Get the value or return null if key was not found
     *
     * @param string $name
     * @return mixed
     */
    public function get(string $name) : mixed
    {
        if ( ! array_key_exists($name, $this->__immutableData)) {
            return null;
        }
        return $this->__immutableData[$name];
    }


    public function all() : array
    {
        return $this->__immutableData;
    }


    public function list() : array 
    {
        return array_keys($this->__immutableData);
    }
    



    public function has(string $name) : bool
    {
        return isset ($this->__immutableData[$name]);
    }


    public function __get ($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        throw new \InvalidArgumentException("Cannot set '$name' on immutable");
    }

    public function __isset($name) : bool
    {
        return isset($this->__immutableData[$name]);
    }

}
