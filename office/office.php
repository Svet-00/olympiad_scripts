<?php

/*
 * (C) Svetozar Volkov <swetozar.volkov@ya.ru>
 */

class plan
{
  private $num_lines = 0;
  private $num_rows;
  private $num_workers;

  private $hx1;
  private $hx2;
  private $hy1;
  private $hy2;

  private $vx1;
  private $vx2;
  private $vy1;
  private $vy2;

  public function __construct($input_file = 'in.txt', $output_file = 'out.png')
  {
    //header('Content-type: image/png');
    $this->read_input($input_file);
    $this->plan_workspace();
    $this->write_output($output_file);
    imagepng($this->image);
  }

  private function read_input($filename)
  {
    $path = __DIR__.DIRECTORY_SEPARATOR.$filename;
    $fd = @fopen($path, 'r');
    if ($fd) {
      $this->num_workers = trim(fgets($fd));

      // echo '<pre>';
      //echo $this->num_workers.'<br>';
      // echo '</pre>';
      fclose($fd);
    } else {
      echo "Не удалось открыть файл: $path";
    }
  }

  private function plan_workspace($value = '')
  {
    //сторона квадрата
    $sqrt = (int) sqrt($this->num_workers);
    //оставшиеся элементы (вне квадрата)
    $others = $this->num_workers - pow($sqrt, 2);

    //Расчитываем количество перегородок
    $dop_lines = (int) ($others / $sqrt);

    if ($others % $sqrt) {
      ++$dop_lines;
    }
    $this->num_rows = $sqrt + $dop_lines;
    echo $dop_lines;

    $this->num_side_lines = (4 * $sqrt) + (2 * $dop_lines);
    $this->num_internal_lines = ((4 * $this->num_workers) - $this->num_side_lines) / 2;
    $this->num_lines = $this->num_side_lines + $this->num_internal_lines;

    //Параметры изображения
    $width = $height = 1000;

    $this->hx1 = 100;
    $this->hx2 = 1000 / $this->num_rows - 30;
    $this->hy1 = 125;
    $this->hy2 = 125;

    $this->vx1 = 100;
    $this->vx2 = 100;
    $this->vy1 = 100;
    $this->vy2 = 1000 / $this->num_rows - 30;

    $this->image = imagecreate($width, $height);

    $bg = imagecolorallocate($this->image, 255, 255, 255);
    $this->black = imagecolorallocate($this->image, 0, 0, 0);
    imagesetthickness($this->image, 5);

    var_dump($this);
    //рисуем картинку
    $this->draw($sqrt);
  }

  private function line_repeat(int $x1, int $y1, int $x2, int $y2,
   int $repeat)
  {
    for ($i = 0; $i < $repeat; ++$i) {
      imageline($this->image, $x1, $y1, $x2, $y2, $this->black);
      $x1 += 1000 / $this->num_rows + 30;
      $x2 += 1000 / $this->num_rows + 30;
    }
  }

  //side_length - длина строки
  //to_draw - всего эл-тов
  private function draw($side_length)
  {
    $to_draw = $this->num_workers;
    while ($to_draw > 0) {
      if ($to_draw > $side_length) {
        $n = $side_length;
        $n1 = $n;
      } else {
        $n = $to_draw;
        $n1 = $side_length;
      }
      $to_draw -= $n;
      //горизонтальные линии
      $this->line_repeat($this->hx1, $this->hy1, $this->hx2, $this->hy2, $n1);
      $this->hy1 += 150;
      $this->hy2 += 150;
      //рисуем вертикальные линии
      $this->line_repeat($this->vx1, $this->vy1, $this->vx2, $this->vy2, $n + 1);
      $this->vy1 += 150;
      $this->vy2 += 150;
    }
    $this->line_repeat($this->hx1, $this->hy1, $this->hx2, $this->hy2, $n);
  }

  private function write_output($filename)
  {
    $path = __DIR__.DIRECTORY_SEPARATOR.$filename;
    $fd = @fopen($path, 'w');
    if ($fd) {
      fwrite($fd, imagepng($this->image));
      fclose($fd);
    } else {
      echo "Не удалось открыть файл: $path";
    }
  }
}
$a1 = new plan('input/input1.txt');
