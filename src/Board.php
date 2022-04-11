<?php declare(strict_types=1);

class Board {
    const EMPTY_LETTER = '.'; // The letter that represents an open square.
    private array $board = [];
    private int $width;
    private int $height;

    public function __construct(int $width, int $height) {
        for ($y = 0; $y < $height; $y++) {
            $this->board[] = [];
            for ($x = 0; $x < $width; $x++) {
                $this->board[$y][] = self::EMPTY_LETTER;
            }
        }
        $this->width = $width;
        $this->height = $height;
    }

    public function toAsciiRepr(): string {
        $result = '';
        foreach ($this->board as $row) {
            foreach ($row as $cell) {
                $result .= $cell;
            }
            $result .= "\n";
        }
        return $result;
    }

    // Update board with placed letters, return score.
    public function scoreMove(Move $move): int {
        $wasBoardEmptyBeforePlacement = $this->isBoardEmpty();

        $affectedSquares = $this->placeLetters($move);
        if (!$wasBoardEmptyBeforePlacement) {
            $this->verifyAdjacent($affectedSquares);
        }
        return $this->score($affectedSquares);
    }

    // I generally do not endorse undoing previously made work, but at this point I'm running out of time.
    private function verifyAdjacent(array $newSquares): void {
        // Restore a copy of board to the state before placing the squares so that we can check
        // if the placed squares were adjacent at the point of placing them.
        $boardCopy = [];
        foreach ($this->board as $i => $row) {
            $boardCopy[$i] = [];
            foreach ($row as $cell) {
                array_push($boardCopy[$i], $cell);
            }
        }
        foreach ($newSquares as $newSquare) {
            $boardCopy[$newSquare->y][$newSquare->x] = self::EMPTY_LETTER;
        }

        foreach ($newSquares as $newSquare) {
            $y = $newSquare->y;
            $x = $newSquare->x;

            if ($y-1 >= 0 && self::EMPTY_LETTER !== $boardCopy[$y-1][$x]) {
                return;
            }
            if ($y+1 < $this->height && self::EMPTY_LETTER !== $boardCopy[$y+1][$x]) {
                return;
            }
            if ($x-1 >= 0 && self::EMPTY_LETTER !== $boardCopy[$y][$x-1]) {
                return;
            }
            if ($x+1 < $this->width && self::EMPTY_LETTER !== $boardCopy[$y][$x+1]) {
                return;
            }
        }
        throw new Exception('No letters of the new word are adjacent to already placed letters.');
    }

    private function isBoardEmpty(): bool {
        foreach ($this->board as $row) {
            foreach ($row as $cell) {
                if (self::EMPTY_LETTER !== $cell) {
                    return false;
                }
            }
        }
        return true;
    }

    private function score(array $affectedSquares): int {
        $points = 0;

        $backtrackedSquares = [];
        foreach ($affectedSquares as $backtrackStart) {
            array_push($backtrackedSquares, $this->backtrackHorizontal($backtrackStart));
            array_push($backtrackedSquares, $this->backtrackVertical($backtrackStart));
        }
        $uniqueBacktrackStarts = array_unique($backtrackedSquares, SORT_STRING);

        foreach ($uniqueBacktrackStarts as $backtrackStart) {
            $points += $this->scoreHorizontalWord($backtrackStart);
            $points += $this->scoreVerticalWord($backtrackStart);
        }

        return $points;
    }

    private function scoreHorizontalWord(Square $wordStart): int {
        $length = 0;
        $points = 0;

        $x = $wordStart->x;
        $y = $wordStart->y;

        while ($x < $this->width && self::EMPTY_LETTER !== $this->board[$y][$x]) {
            $length++;
            $points += letterToPoints($this->board[$y][$x]);

            $x++;
        }

        return $points * (int)($length > 1);
    }

    private function scoreVerticalWord(Square $wordStart): int {
        $length = 0;
        $points = 0;

        $x = $wordStart->x;
        $y = $wordStart->y;

        while ($y < $this->height && self::EMPTY_LETTER !== $this->board[$y][$x]) {
            $length++;
            $points += letterToPoints($this->board[$y][$x]);

            $y++;
        }

        return $points * (int)($length > 1);
    }

    private function backtrackHorizontal(Square $square): Square {
        while (1) {
            if (0 === $square->x) {
                return $square;
            }

            if (self::EMPTY_LETTER === $this->board[$square->y][$square->x-1]) {
                return $square;
            }

            $square->x--;
        }

        return $square;
    }

    private function backtrackVertical(Square $square): Square {
        while (1) {
            if (0 === $square->y) {
                return $square;
            }

            if (self::EMPTY_LETTER === $this->board[$square->y-1][$square->x]) {
                return $square;
            }

            $square->y--;
        }

        return $square;
    }

    private function verifyInBounds($x, $y): void {
        if ($x >= $this->width) {
            throw new Exception('Word cannot be placed out of bounds.');
        }
        if ($y >= $this->height) {
            throw new Exception('Word cannot be placed out of bounds.');
        }
    }

    // Returns list of actually affected squares since letter placing sequences may have holes in them.
    private function placeLetters(Move $move): array {
        $squaresAffected = [];

        $x = $move->x;
        $y = $move->y;

        // The first letter must be placed on a free square, but the remaining letters skip over occupied
        // squares so that words can be placed vertically using a letter from a horizontal word, for example.
        $placingFirstLetter = true;

        foreach ($move->letters as $letter) {
            $this->verifyInBounds($x, $y);

            if ($placingFirstLetter && self::EMPTY_LETTER !== $this->board[$y][$x]) {
                throw new Exception(sprintf('Cannot place letter "%s" at occupied square %dx%d.', $letter, $x, $y));
            }

            // Skip over letters until we find an empty space, so that overlapping words can be placed.
            while (self::EMPTY_LETTER !== $this->board[$y][$x]) {
                if ('x' === $move->direction) {
                    $x++;
                } else {
                    $y++;
                }

                $this->verifyInBounds($x, $y);
            }

            $this->board[$y][$x] = $letter;
            array_push($squaresAffected, new Square($x, $y));

            if ('x' === $move->direction) {
                $x++;
            } else {
                $y++;
            }

            $placingFirstLetter = false;
        }

        return $squaresAffected;
    }
}
