<?php

/*
 * (C) Svetozar Volkov <swetozar.volkov@ya.ru>
 */

class fraction
{
  //variables
  private $fractions = [];
  private $result = [];

  public function __construct($input, $output)
  {
    $this->get_data($input);
    $this->process_fractions();

    $this->write_data($output);
  }

  private function get_data($input_file)
  {
    $path = __DIR__.DIRECTORY_SEPARATOR.$input_file;
    $fd = @fopen($path, 'r');
    if (!$fd) {
      echo "Не удалось прочитать содержимое файла $path";
    }
    while ($str = fgets($fd)) {
      $tmp = explode('.', trim($str));
      if (!$tmp[1]) {
        $tmp[1] = 0;
      }
      $this->fractions[] = [$tmp[0], $tmp[1]];
    }
  }

  private function process_fractions()
  {
    foreach ($this->fractions as $num) {
      $int = $num[0] > 0 ? $num[0] : '';
      $fract = $num[1] > 0 ? $num[1] : '';
      if ($fract > 0) {
        $mult = '1'.str_repeat('0', strlen((string) $fract));
        if (0 === $mult % $fract) {
          $fract = '1/'.($mult / $fract);
        } else {
          $fract = "$fract/$mult";
        }
      }
      $this->result[] = trim($int.' '.$fract);
    }
  }

  private function write_data($output_file)
  {
    $path = __DIR__.DIRECTORY_SEPARATOR.$output_file;
    $fd = fopen($path, 'w');
    if ($fd) {
      foreach ($this->result as $val) {
        echo $val.'<br>';
        fwrite($fd, $val.PHP_EOL);
      }

      fclose($fd);
    } else {
      echo "Не удалось записать ответ в файл: $path";
    }
  }
}

$a1 = new fraction('input.txt', 'output.txt');
