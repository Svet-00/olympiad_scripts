<?php

/*
 * (C) Svetozar Volkov <swetozar.volkov@ya.ru>
 */

class name
{
  //variables

  public function __construct($input, $output)
  {
    $this->get_data($input);
    //some actions

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
      //process input_data
    }
  }

  private function write_data($output_file)
  {
    $path = __DIR__.DIRECTORY_SEPARATOR.$output_file;
    $fd = fopen($path, 'w');
    if ($fd) {
      fwrite($fd, $this->count);
      fclose($fd);
    } else {
      echo "Не удалось записать ответ в файл: $path";
    }
    echo "{$this->count}</b></pre>";
  }
}
