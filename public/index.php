<?php declare(strict_types=1);

require '../src/Player.php';
require '../src/Tile.php';
require '../src/TileBag.php';
require '../src/Square.php';
require '../src/Request.php';
require '../src/Move.php';
require '../src/RandomGenerator.php';
require '../src/Board.php';
require '../src/Game.php';
require '../src/letterToPoints.php';

header('Content-Type: text/plain');

try {
    $pathInfo = $_SERVER['PATH_INFO'] ?? '';
    $request = new Request($pathInfo);
    $random = new RandomGenerator($request->seed);
    $tileBag = new TileBag($random);
    $board = new Board(15, 15);
    $game = new Game($tileBag, $board);
    foreach ($request->moves as $move) {
        $game->makeMove($move);
    }

    echo $board->toAsciiRepr();
    printf("PLAYER: %s\n", $game->getCurrentPlayer()->getName());
    printf("HAND: %s\n", $game->getCurrentPlayer()->showHand());
    foreach ($game->getPlayers() as $player) {
        printf("PLAYER %s SCORE: %d\n", $player->getName(), $player->getScore());
    }
} catch(Exception $e) {
    printf("ERROR: %s\n", $e->getMessage());
}

