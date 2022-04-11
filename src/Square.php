<?php declare(strict_types=1);

class Square {
    public function __construct(
        public int $x,
        public int $y
    ){}

    // Allows us to use array_unique on objects of this class
    public function __toString() {
        return sprintf('%x.%x', $this->x, $this->y);
    }
}
