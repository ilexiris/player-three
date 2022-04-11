<?php declare(strict_types=1);

require 'NumberGenerator.php';

// This is highly volatile as if anyone else uses mt_rand, they mess up the number series we depend on.
class RandomGenerator implements NumberGenerator {
    public function __construct(int $seed) {
        mt_srand($seed);
    }
    public function next(int $max) {
        return mt_rand(0, $max);
    }
}
