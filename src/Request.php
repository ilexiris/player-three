<?php declare(strict_types=1);

class Request {
    public int $seed;
    public array $moves;
    public function __construct(string $pathInfo) {
        $cleanPathInfo = trim($pathInfo, '/');
        $parts = explode('/', $cleanPathInfo);
        $this->seed = (int)array_shift($parts);
        if (0 === $this->seed) {
            // Disallow 0 as seed to quickly disallow putting garbage in the seed section,
            // or visiting with an empty path.
            throw new Exception('Bad seed.');
        }
        $this->moves = array_map(function($part){ return new Move($part); }, $parts);
    }
}
