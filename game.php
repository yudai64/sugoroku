<?php
  class Game {

    private $board = ""; //マス目の番号、アクション、コメントが格納された配列を、マス分格納した配列
    private $players = array(); //人オブジェクトが入った配列
    private $dice = ""; // 1~6までの数字が入った配列
    
    //ボード設定
    function setBoard($board) {
      $this->board = $board->returnSquares();
    }

    //プレイヤー追加
    function addPlayer($player) {
      $this->players[] = $player;
    }

    //サイコロ設定
    function setDice($dice) {
      $this->dice = $dice->returnNumbers();
    }


    //ゲームスタート
    function start() {
      //ゴール地点設定
      $squares = count($this->board) -1;
      //ターン数初期設定
      $turn = 1;
      
      //現在の位置を表す配列
      $now_positions = array($this->players[0]->returnNowPosition(), $this->players[1]->returnNowPosition());

      //どちらかがゴールするまで繰り返し
      while ($now_positions[0] != $squares && $now_positions[1] != $squares) {
        echo $turn . "ターン目</br>";
        
        //それぞれサイコロ振って進める
        foreach($this->players as $player) {
          $name = $player->returnName();
          echo $name . "の番です</br>";
          //現在の位置もってくる
          switch($name) {
            case "Taro":
               $now_position = $now_positions[0];
            break;
            case "Jiro":
              $now_position = $now_positions[1];
            break;
          }
          if ($now_position == 0) {
            echo "現在スタート地点です。</br>";
          } else {
            echo "現在の位置は" . $now_position . "マス目です。</br>";
          }
          //サイコロ振る
          $number = $this->throwDice();
          echo $number . "の目がでました。</br>" . $number . "マス進みます。</br>";
          //現在のマス目にサイコロでてた数分を足す。
          $now_position += $number;
          //足した数がゴールより大きくなった場合、そのぶんだけ戻る
          if ($now_position > $squares) {
            echo "ちょうどゴール出来なかった場合、その分だけ戻ります。</br>";
            $now_position = $squares - ($now_position - $squares);
          }

          $effect = $this->board[$now_position][1];
          //止まったマスのコメント表示
          echo $this->board[$now_position][2] . "</br>";
          //コメントの支持にしたがってマス目移動
          $now_position += $effect;

          //ちょうどゴールに到達したら終わり
          if ($now_position == $squares) {
            echo $name. "はゴールしました！.</br>" . $name . "の勝ちです！";
            exit();
          } else {
            echo "現在の位置は" . $now_position . "マス目です。</br></br>";
          }

          //現在の位置を格納
          switch($name) {
            case "Taro":
              $now_positions[0] = $now_position;
            break;
            case "Jiro":
              $now_positions[1] = $now_position;
            break;
          }

        }
        //ターンカウント
        $turn ++;
      }       
    }

    //サイコロを振る。1~6の数字がかえってくる。
    function throwDice() {
      $key = array_rand($this->dice);
      return $this->dice[$key];
    }

  }
?>