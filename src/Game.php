<?php declare(strict_types=1);

class Game {
    const TARGET_HAND_SIZE = 7;
    const PLAYERS = 2;
    private array $players = [];
    private int $turn = 0;

    public function __construct(
        private TileBag $tileBag,
        private Board $board, 
    ) {
        for ($i = 0; $i < self::PLAYERS; $i++) {
            $this->players[] = new Player($i+1);
        }

        foreach ($this->players as $player) {
            $this->drawUntilSatisfied($player);
        }
    }

    private function drawUntilSatisfied(Player $player): void {
        while ($this->tileBag->hasTiles() && $player->tilesOnHand() < self::TARGET_HAND_SIZE) {
            $player->addToHand($this->tileBag->draw());
        }
    }

    public function makeMove(Move $move): void {
        $points = $this->board->scoreMove($move);
        $player = $this->getCurrentPlayer();
        $player->addPoints($points);
        $player->loseTiles($move->letters);
        $this->drawUntilSatisfied($player);
        $this->turn++;
    }

    public function getCurrentPlayer(): Player {
        assert(array_is_list($this->players));
        return $this->players[$this->turn % self::PLAYERS];
    }
    
    public function getPlayers(): array {
        return $this->players;
    }
}
