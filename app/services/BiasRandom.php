<?php

class BiasRandom
{
    protected $data = [];

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function addElement($name, $weight)
    {
        if (is_string($name) && is_numeric($weight)) {
            $this->data[$name] = $weight;
            return true;
        }

        return false;
    }

    public function removeElement($name)
    {
        unset($this->data[$name]);
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    private function getRandom($data)
    {
        $total = 0;
        $distribution = [];
        foreach ($data as $name => $weight) {
            $total += $weight;
            $distribution[$name] = $total;
        }
        $rand = mt_rand(0, $total - 1);
        foreach ($distribution as $name => $weight) {
            if ($rand < $weight) {
                return $name;
            }
        }
    }

    public function random($count = 1)
    {
        $data = $this->data;
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $name = $this->getRandom($data);
            $result[] = $name;
            unset($data[$name]);
        }

        return $result;
    }
}