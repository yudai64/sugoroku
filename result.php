<?php
  require_once("game.php");
  require_once("board.php");
  require_once("player.php");
  require_once("dice.php");

  $game = new Game;
  $game->setBoard(new Board("board.csv"));
  $game->addPlayer(new Player("Taro"));
  $game->addPlayer(new Player("Jiro"));
  $game->setDice(new Dice);
  $game->start();
?>