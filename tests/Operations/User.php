<?php

namespace Operations;

final class User
{
    private $id;
    private $name;
    public $prop1 = 10;

    public function __construct(string $name, int $id = 1)
    {
        $this->name = $name;
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isValid(): bool
    {
        return $this->name !== 'Mike';
    }

    public function getProp2(): int
    {
        return 20;
    }
}