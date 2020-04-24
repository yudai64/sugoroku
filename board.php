<?php
  class Board {

    private $squares = array();

    function __construct($file) {
      //ボードの情報をboard.csvからもってくる
      $f = fopen($file, "r");
      while(($data = fgets($f)) !== FALSE) {
        $this->squares[] = (int)$data;
      }
      fclose($f);
    }

    function returnSquares() {
      return $this->squares;
    }
  }
?>