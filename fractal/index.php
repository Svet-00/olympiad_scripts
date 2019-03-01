<?php

/*
 * (C) Svetozar Volkov <swetozar.volkov@ya.ru>
 */

 set_time_limit(300);

class fractal
{
  private $img_cfg; //параметры изображения и линии
  private $cmds; //команды
  private $curr_angle; //текущее значение угла
  private $angle; //угол
  private $num_iter; //количество итераций
  private $rules; //правила преобразования команд
  private $img; //объект изображения
  private $line; //объект линии
  private $length; //длина отрезка
  private $colour; //цвет линии
  private $sx = array();
  private $sy = array();
  private $sangle = array();

  public function __construct($in, $out = 'out.png')
  {
    header('Content-type: image/png');
    $this->get_data($in);
    $this->process_rules();

    $colour = array(rand(0, 200), rand(0, 200), rand(0, 200));

    $this->init_img($colour);
    $this->draw();
    //var_dump($this);
    imagepng($this->img);
    imagedestroy($this->img);
  }

  //функция заполняет свойства объекта входными данными
  private function get_data($input_file)
  {
    $path = __DIR__.DIRECTORY_SEPARATOR.$input_file;
    $fd = @fopen($path, 'r');
    if (!$fd) {
      echo "Не удалось прочитать содержимое файла $path";
    }
    for ($i = 0;; ++$i) {
      $str = fgets($fd);
      $str = trim($str);
      if (!$str) {
        break;
      }
      switch ($i) {
  case 0:
  $this->img_cfg = explode(' ', $str);
  break;
  case 1:
  $this->cmds = $str;
  break;
  case 2:
  $this->angle = (int) $str;
  break;
  case 3:
  $this->num_iter = (int) $str; break;
  default:
  $tmp = explode(' ', $str);
  $this->rules[$tmp[0]] = $tmp[1];
  break;
  }
    }
  }

  //обработка команд согласно полученным правилам
  private function process_rules()
  {
    //заменяем переменные значением
    //выполняем нужное количество проходов

    $str = '';
    for ($a = 0; $a < $this->num_iter - 1; ++$a) {
      for ($i = 0; $i < strlen($this->cmds); ++$i) {
        $char = $this->cmds[$i];
        $rule = $this->rules[$char];
        if (isset($rule)) {
          $str .= $rule;
        } else {
          $str .= $char;
        }
      }
      $this->cmds = $str;
      $str = '';
    }
  }

  //инициализируем объекты изображения, линий и т.д.
  private function init_img($colour)
  {
    $this->img = imagecreate((int) $this->img_cfg[0], (int) $this->img_cfg[1]);
    imagesetthickness($this->img, 1);
    $this->length = (int) $this->img_cfg[2];
    $this->x = (int) $this->img_cfg[3];
    $this->y = (int) $this->img_cfg[4];
    $this->curr_angle = (int) $this->img_cfg[5];
    unset($this->img_cfg);
    $bg = imagecolorallocate($this->img, 255, 255, 255);
    $this->colour = imagecolorallocate($this->img, $colour[0], $colour[1], $colour[2]);
  }

  private function draw()
  {
    for ($i = 0; $i < strlen($this->cmds); ++$i) {
      switch ($this->cmds[$i]) {
  case 'F':
  $this->draw_line();
  break;
  case '+':
  $this->curr_angle += $this->angle;
  break;
  case '-':
  $this->curr_angle -= $this->angle;
  break;
  case '[':
  $this->sx[] = $this->x;
  $this->sy[] = $this->y;
  $this->sangle[] = $this->curr_angle;
  break;
  case ']':
  $this->x = array_pop($this->sx);
  $this->y = array_pop($this->sy);
  $this->curr_angle = array_pop($this->sangle);
  break;
  default:
  break;
  }
    }
  }

  private function draw_line()
  {
    $this->curr_angle = $this->curr_angle % 360;
    $rad = deg2rad($this->curr_angle);
    $x2 = $this->x + ($this->length * cos($rad));
    $y2 = $this->y + ($this->length * sin($rad));
    imageline($this->img, $this->x, $this->y, $x2, $y2, $this->colour);
    $this->x = $x2;
    $this->y = $y2;
  }
}
$s = microtime(true);
$a1 = new fractal('input1.txt', 'out.png');
$f = microtime(true);

$time = $f - $s;
$fd = @fopen('mytime1.txt', 'a');
fwrite($fd, $time.PHP_EOL);
