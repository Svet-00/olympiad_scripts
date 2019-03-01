<?php

/*
 * (C) Svetozar Volkov <swetozar.volkov@ya.ru>
 */

class simple
{
  //variables
  private $num;
  private $lst;

  public function __construct($input, $output)
  {
    $this->get_data($input);
    $this->find_simple();

    $this->write_data($output);
  }

  private function get_data($input_file)
  {
    $path = __DIR__.DIRECTORY_SEPARATOR.$input_file;
    $fd = @fopen($path, 'r');
    if (!$fd) {
      echo "Не удалось прочитать содержимое файла $path";
    }
    $this->num = trim(fgets($fd));
  }

  private function find_simple()
  {
    //массив от 0 до нум
    $a = range(0, $this->num);
    //убираем единицу
    $a[1] = 0;
    //массив простых чисед
    $this->lst = [];

    for ($i = 2; $i <= $this->num; ++$i) {
      if (0 !== $a[$i]) {
        $this->lst[] = $a[$i];
        if ($i < ($this->num - $i)) {
          $r = range($i, $this->num + 1, $i);
          foreach ($r as $j) {
            $a[$j] = 0;
          }
        }
      }
    }
    //echo implode(' ', $this->lst);
  //var_dump($this);
  }

  private function write_data($output_file)
  {
    $path = __DIR__.DIRECTORY_SEPARATOR.$output_file;
    $fd = fopen($path, 'w');
    if ($fd) {
      fwrite($fd, implode(' ', $this->lst));
      fclose($fd);
    } else {
      echo "Не удалось записать ответ в файл: $path";
    }
    //echo "{$this->count}</b></pre>";
  }
}
$s = microtime(true);
$a1 = new simple('input.txt', 'output.txt');
$f = microtime(true);
echo $f - $s;
