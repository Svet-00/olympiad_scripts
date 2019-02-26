<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Заголовок</title>
  </head>
  <body>
<?php

class graph
{
  private $tag_names = array();
  private $graph = array();
  private $count = 0;
  private $linked_elements = array();
  private $offset = 0;

  public function __construct($input_file, $output_file)
  {
    $this->read_input($input_file);
    $this->count_needed_links();
    $this->write_output($output_file);
  }

  private function read_input($filename)
  {
    $path = __DIR__.DIRECTORY_SEPARATOR.$filename;
    $fd = fopen($path, 'r');
    if ($fd) {
      $it = 0;
      while ($buffer = fgets($fd)) {
        $buffer = trim($buffer);
        $tags = explode(',', $buffer);

        for ($i = 0; $i < count($tags); ++$i) {
          if (!empty($this->tag_names)) {
            if (!in_array($tags[$i], $this->tag_names, true)) {
              $this->tag_names[] = $tags[$i];
            }
          } else {
            $this->tag_names[] = $tags[$i];
          } //array_unique

          if ($i > 0) {
            $this->graph[$tags[$i]][$tags[$i - 1]] = 1;
            $this->graph[$tags[$i - 1]][$tags[$i]] = 1;
          }
        }
      }

      // echo '<pre>';
      // var_dump($this->graph);
      // var_dump($this->tag_names);
      // echo '</pre>';
      fclose($fd);
    } else {
      echo "Не удалось открыть файл: $path";
    }
  }

  private function mark_linked()
  {
    $tag = $this->linked_elements[$this->offset];
    if (is_array($this->graph[$tag])) {
      foreach ($this->graph[$tag] as $key => $value) {
        if (in_array((string) $key, $this->linked_elements, true)) {
          continue;
        }
        $this->linked_elements[] = (string) $key;
      }
      unset($this->graph[$tag]);
      ++$this->offset;
      //echo 'связанные тэги<br>';
      //array_unique($this->linked_elements);
      //var_dump($this->linked_elements);

      return true;
    }

    return false;
  }

  private function count_needed_links()
  {
    while (true) {
      $this->linked_elements[0] = array_shift($this->tag_names);
      //echo "{$thislinked_elements[0]}";
      $go = true;
      while ($go) {
        $go = $this->mark_linked();
      }
      $this->tag_names = array_diff($this->tag_names, $this->linked_elements);
      $this->linked_elements = array();
      $this->offset = 0;
      //echo 'Имена тэгов<br>';
      //var_dump($this->tag_names);
      if (!empty($this->tag_names)) {
        ++$this->count;
      } else {
        break;
      }
    }
  }

  private function write_output($filename)
  {
    $path = __DIR__.DIRECTORY_SEPARATOR.$filename;
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

$start = microtime(true);

for ($i = 1; $i < 15; ++$i) {
  $input = 'graph_input'.DIRECTORY_SEPARATOR."input$i.txt";
  $output = 'graph_output'.DIRECTORY_SEPARATOR."output$i.txt";
  echo "<pre style=\"font-size:14pt;\">Результат #$i:     <b>";
  ${'a'.$i} = new graph($input, $output);
}

$end = microtime(true);
$time = $end - $start;
echo "<br>Скрипт выполнен за $time секунд.";

?>
  </body>
</html>
