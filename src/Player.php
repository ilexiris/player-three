<?php declare(strict_types=1);

class Player {
    private int $points = 0;
    private array $tiles = [];

    public function __construct(private $name) {
    }

    public function getName() {
        return $this->name;
    }

    public function addToHand(Tile $tile): void {
        array_push($this->tiles, $tile);
    }

    public function tilesOnHand(): int {
        return count($this->tiles);
    }

    public function showHand(): string {
        return implode('', array_map(
            function(Tile $tile) { return $tile->letter; },
            $this->tiles,
        ));
    }

    public function loseTiles(array $letters): void {
        while ($letter = array_shift($letters)) {
            $unset = null;
            foreach ($this->tiles as $i => $tile) {
                if ($tile->letter === $letter) {
                    $unset = $i;
                    break;
                }
            }
            assert(
                null !== $unset,
                new Exception('Attempted to place letter not part of player\'s hand.')
            );
            unset($this->tiles[$i]);
        }
    }

    public function addPoints(int $points): void {
        $this->points += $points;
    }

    public function getScore(): int {
        return $this->points;
    }
}
