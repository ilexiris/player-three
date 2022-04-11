<?php declare(strict_types=1);

class TileBag {
    private array $tiles = [];
    public function __construct(
        private NumberGenerator $numberGen,
    ) {
        $this->tiles = [
            new Tile('A'),
            new Tile('A'),
            new Tile('A'),
            new Tile('A'),
            new Tile('A'),
            new Tile('A'),
            new Tile('A'),
            new Tile('A'),
            new Tile('A'),

            new Tile('B'),
            new Tile('B'),

            new Tile('C'),
            new Tile('C'),

            new Tile('D'),
            new Tile('D'),
            new Tile('D'),
            new Tile('D'),

            new Tile('E'),
            new Tile('E'),
            new Tile('E'),
            new Tile('E'),
            new Tile('E'),
            new Tile('E'),
            new Tile('E'),
            new Tile('E'),
            new Tile('E'),
            new Tile('E'),
            new Tile('E'),
            new Tile('E'),

            new Tile('F'),
            new Tile('F'),

            new Tile('G'),
            new Tile('G'),
            new Tile('G'),

            new Tile('H'),
            new Tile('H'),

            new Tile('I'),
            new Tile('I'),
            new Tile('I'),
            new Tile('I'),
            new Tile('I'),
            new Tile('I'),
            new Tile('I'),
            new Tile('I'),
            new Tile('I'),

            new Tile('J'),

            new Tile('K'),

            new Tile('L'),
            new Tile('L'),
            new Tile('L'),
            new Tile('L'),

            new Tile('M'),
            new Tile('M'),

            new Tile('N'),
            new Tile('N'),
            new Tile('N'),
            new Tile('N'),
            new Tile('N'),
            new Tile('N'),

            new Tile('O'),
            new Tile('O'),
            new Tile('O'),
            new Tile('O'),
            new Tile('O'),
            new Tile('O'),
            new Tile('O'),
            new Tile('O'),

            new Tile('P'),
            new Tile('P'),

            new Tile('Q'),

            new Tile('R'),
            new Tile('R'),
            new Tile('R'),
            new Tile('R'),
            new Tile('R'),
            new Tile('R'),

            new Tile('S'),
            new Tile('S'),
            new Tile('S'),
            new Tile('S'),

            new Tile('T'),
            new Tile('T'),
            new Tile('T'),
            new Tile('T'),
            new Tile('T'),
            new Tile('T'),

            new Tile('U'),
            new Tile('U'),
            new Tile('U'),
            new Tile('U'),

            new Tile('V'),
            new Tile('V'),

            new Tile('W'),
            new Tile('W'),

            new Tile('X'),

            new Tile('Y'),
            new Tile('Y'),

            new Tile('Z'),
        ];
    }

    public function draw(): Tile {
        assert(array_is_list($this->tiles));
        $i = $this->numberGen->next(count($this->tiles)-1);
        $result = $this->tiles[$i]; 

        unset($this->tiles[$i]);
        // Inefficient reordering of the array. (The way we delete random items and this datatype do not play well together.)
        $this->tiles = array_values($this->tiles);

        return $result;
    }

    public function hasTiles(): bool {
        return count($this->tiles) > 0;
    }
}
