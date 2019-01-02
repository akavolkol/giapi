<?php

namespace App\Presenters;

use ArrayAccess;

abstract class Presenter
{
    /**
     * @var array
     */
    protected $map = [];

    /**
     * @var int|null
     */
    protected $modifier;

    /**
     * @param mixed $data
     * @return array
     */
    public function present(ArrayAccess $data): array
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

    /**
     * @param int $modifier
     */
    public function setModifier(int $modifier)
    {
        $this->modifier = $modifier;
    }

    /**
     * @return int|null
     */
    public function getModifier(): ?int
    {
        return $this->modifier;
    }
}
