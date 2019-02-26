<?php

/*
 * (C) Svetozar Volkov <swetozar.volkov@ya.ru>
 */

$s = microtime(true);

  $fileName = 'input6.txt';
  $f = @fopen(__DIR__.DIRECTORY_SEPARATOR.$fileName, 'r');

  if (!$f) {
    die('Could not open file: '.$fileName);
  }

  $commands = '';
  $rules = array();

  //?????? ??????
  $line = trim(fgets($f));
  $data = explode(' ', $line);
  $IMAGE_WIDTH = $data[0];
  $IMAGE_HEIGHT = $data[1];
  $spd = $data[2];
  $START_X = $data[3];
  $START_Y = $data[4];
  $angle = $data[5];

  //???????
  $commands = trim(fgets($f));

  //???? ????????
  $ROT_ANGLE = trim(fgets($f));

  //???-?? ????????
  $NUM_ITS = trim(fgets($f));

  while ($line = trim(fgets($f))) {
    $rules[$line[0]] = substr($line, 2);
  }

  for ($i = 0; $i < $NUM_ITS - 1; ++$i) {
    $commands = nextPermutaion($commands, $rules);
    //$spd /= sqrt(2);
  //$angle -= 45;
  }

  header('Content-Type: image/png');

  $img = imagecreatetruecolor($IMAGE_WIDTH, $IMAGE_HEIGHT);
  $color = imagecolorallocate($img, 0, 0, 0);
  imagefill($img, 0, 0, imagecolorallocate($img, 255, 255, 255));
  imagesetthickness($img, 1);

  $turtle = new Turtle($img, $START_X, $START_Y, $spd, $angle % 360, $ROT_ANGLE);

  $len = strlen($commands);
  for ($i = 0; $i < $len; ++$i) {
    switch ($commands[$i]) {
  case 'F':
  $turtle->forward();
  break;
  case '+':
  $turtle->turnLeft();
  break;
  case '-':
  $turtle->turnRight();
  break;
  case '[':
  $turtle->pushState();
  break;
  case ']':
  $turtle->popState();
  break;
  }
  }

  imagestring($img, '10', 0, 0, strlen($commands), $color);
  imagepng($img);

$f = microtime(true);
$time = $f - $s;
$fd = @fopen('time6.txt', 'w');
fwrite($fd, $time);
  imagedestroy($img);

  class Turtle
  {
    private $x = 0;
    private $y = 0;

    private $dir = array(0, -1);
    private $speed = 50;

    private $img;
    private $color;
    private $angle = 0;
    private $rotAngle = 90;

    private $states = array();

    public function __construct(&$image, $x, $y, $speed, $angle, $rotAngle)
    {
      $this->x = $x;
      $this->y = $y;
      $this->speed = $speed;
      $this->angle = $angle;
      $this->rotAngle = $rotAngle;
      $this->img = $image;
      $this->color = imagecolorallocate($this->img, rand(0, 200), rand(0, 200), rand(0, 200));
    }

    public function forward()
    {
      $radAngle = deg2rad($this->angle);
      $dir = array(cos($radAngle), sin($radAngle));

      imageline($this->img,
  $this->x,
  $this->y,
  $this->x + ($dir[0] * $this->speed),
  $this->y + ($dir[1] * $this->speed),
  $this->color);

      $this->x += $dir[0] * $this->speed;
      $this->y += $dir[1] * $this->speed;
    }

    public function turnLeft()
    {
      $this->angle += $this->rotAngle;
    }

    public function turnRight()
    {
      $this->angle -= $this->rotAngle;
    }

    public function pushState()
    {
      $this->states[] = array($this->x, $this->y, $this->angle);
    }

    public function popState()
    {
      $state = array_pop($this->states);
      if (empty($state)) {
        return;
      }
      $this->x = $state[0];
      $this->y = $state[1];
      $this->angle = $state[2];
    }
  }

  function nextPermutaion($str, $r)
  {
    //echo $str."<br><br>";
    $newStr = '';
    for ($i = 0; $i < strlen($str); ++$i) {
      $currChar = $str[$i];
      if (isset($r[$currChar])) {
        $newStr .= $r[$currChar];
      } else {
        $newStr .= $currChar;
      }
    }

    return $newStr;
  }
