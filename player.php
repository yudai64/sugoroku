<?php
  class Player {
    private $name ="";
    private $now_position = 0;

    function __construct($name) {
      $this->name = $name;
    }

    function returnName() {
      return $this->name;
    }

    //現在の位置をかえす
    function returnNowPosition() {
      return $this->now_position;
    }

  }
?>