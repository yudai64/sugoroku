<?php
  class Game {

    private $board = ""; //マス目のイベントを意味する数字を格納した配列
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
      $squares = count($this->board);
      echo "今回のマス目は" . $squares . "です</br>";
      //ターン数初期設定
      $turn = 1;
      
      //現在の位置を表す配列
      $now_positions = array();
      //トータルの移動を表した配列
      $totals = array();

      foreach($this->players as $player) {
        $now_positions[] = $player->returnNowPosition();
        $totals[] = 0;
      }


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
               $total = $totals[0];
            break;
            case "Jiro":
              $now_position = $now_positions[1];
              $total = $totals[1];
            break;
          }

          //ターン開始時の現在地表示
          if ($now_position == 0) {
            echo "現在の位置はスタート地点です。</br>";
          } else {
            echo "現在の位置は" . $now_position . "マス目です。</br>";
          }

          //サイコロ振る
          $number = $this->throwDice();
          echo $number . "の目がでました。</br>" . $number . "マス進みます。</br>";
          //現在のマス目にサイコロでてた数分を足す
          $now_position += $number;
          //トータル移動距離にサイコロででた分をたす
          $total += $number;

          if ($now_position == $squares) {
            //ちょうどゴールに到達した場合、ゲーム終了
            echo $name. "はゴールしました！.</br>" . $name . "の勝ちです！";
            echo $name . "は合計で" . $total . "マス移動しました。";
            exit();
          } else if ($now_position > $squares) {
            //足した数がゴールより大きくなった場合、そのぶんだけ戻る
            echo "ちょうどゴール出来なかった場合、その分だけ戻ります。</br>";
            $now_position = $squares - ($now_position - $squares);
          }

          $effect = $this->board[$now_position];
          $count = 1;

          //マス目の支持で移動したマス目にまた支持があった場合、3回まで指示は有効
          while ($count <= 3) {
            //支持にしたがってマス目移動
            if ($effect == 100) {
              //100はスタートに戻るを意味するマス。スタートに戻させる
              echo "マス目にはスタート地点に戻ると書いてあります。スタート地点に戻ります。。。</br>";
              $now_position = 0;
            break;
            } else {
              if ($effect > 0) {
                echo "マス目には" . $effect . "進むと書いてあります。" . $effect . "マス進みます！</br>";
                if ($effect - ($squares - $now_position) > $squares) {
                  //ゴールから折り返してスタート地点まで戻った場合
                  $total += ($squares - $now_position) + $squares;
                } else {
                  $total += $effect;
                }
              } else if ($effect < 0) {
                echo "マス目には" . abs($effect) . "戻ると書いてあります。" . abs($effect) . "マス戻ります。。</br>";
                if ($now_position - $effect < 0) {
                  //スタートまで戻った場合
                  $total += $now_position;
                } else {
                  $total += abs($effect);
                }
              } else {
                echo "マス目には何も書いてありませんでした。</br>";
              break;
              }
              $now_position += $effect;

              if ($now_position == $squares) {
                //マス目の指示でゴールに到達した場合もゲーム終了
                echo $name. "はゴールしました！" . $name . "の勝ちです！";
                echo $name . "は合計で" . $total . "マス移動しました";
                exit();
              // }
              } else if ($now_position > $squares) {
                //足した数がゴールより大きくなった場合、そのぶんだけ戻る
                echo "ちょうどゴール出来なかった場合、その分だけ戻ります。</br>";
                $now_position = $squares - ($now_position - $squares);
              } 
              
              //マス目が0より小さくなった場合はスタート地点から
              if ($now_position < 0) {
                echo "これ以上戻れないのでスタート地点まで戻ります。</br>";
                $now_position = 0;
              break;
              }

            }

            //指示で動いたあとのマス目の支持を変数に入れなおす。
            $effect = $this->board[$now_position];

            //現在の位置を表示
            if ($count != 3) {
              echo "現在の位置は" . $now_position . "マス目です。</br>";
            }
            //カウント
            $count++;
          }

          //ターン終了後の現在地を表示
           if ($now_position == 0){
            echo "現在の位置はスタート地点です。</br></br>";
           } else {
            echo "現在の位置は" . $now_position . "マス目です。</br></br>";
           }

          //現在の位置とトータルの移動距離を格納
          switch($name) {
            case "Taro":
              $now_positions[0] = $now_position;
              $totals[0] = $total;
            break;
            case "Jiro":
              $now_positions[1] = $now_position;
              $totals[1] = $total;
            break;
          }

        }
        //ターンカウント
        $turn ++;

        //ターンが40以上かかる場合、トータルの移動距離を表示して強制終了
        if ($turn > 40) {
          echo "決着がつきません。すごろくのマス目に問題があるかもしれません。</br>";
          foreach($this->players as $player) {
            $name = $player->returnName();
            switch($name) {
              case "Taro":
                $total = $totals[0];
              break;
              case "Jiro":
                $total = $totals[1];
              break;
            }
            echo $name . "は合計で" . $total . "マスだけ移動しました。</br>";
          }
          exit();
        }

        echo "</br>";
      }       
    }

    //サイコロを振る。1~6の数字がかえってくる。
    function throwDice() {
      $key = array_rand($this->dice);
      return $this->dice[$key];
    }

  }
?>