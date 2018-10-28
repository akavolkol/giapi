<?php

namespace App\Presenters;

abstract class Presenter
{
    protected $map = [];

    protected $modifier;

    public function present($data)
    {
        return array_reduce(array_keys($data), function ($carry, $key) use ($data) {
            if (isset($this->map[$key])) {
                $carry[$this->map[$key]] = $data[$key];
            } else {
                $carry[$key] = $data[$key];
            }
            return $carry;
        }, []);
    }

    public function setModifier(int $modifier)
    {
        $this->modifier = $modifier;
    }

    public function getModifier()
    {
        return $this->modifier;
    }
}
