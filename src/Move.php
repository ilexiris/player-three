<?php declare(strict_types=1);

class Move {
    public int $x;
    public int $y;
    public string $direction;
    public array $letters;
    public function __construct(string $notated) {
        if (1 !== preg_match('/[0-9a-e][xy][0-9a-e][A-Z]+/', $notated)) {
            throw new Exception('Bad notation.');
        }
        $chars = str_split($notated);
        $this->x = hexdec(array_shift($chars));
        $this->direction = array_shift($chars);
        $this->y = hexdec(array_shift($chars));
        $this->letters = $chars;
    }
}
