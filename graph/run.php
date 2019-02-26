<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

new MyGraph();

class MyGraph
{
  private $filename = '';
  private $inputFilename = '';

  private $dataArray = array();

  private $visited = array();

  /**
   * MyGraph constructor.
   *
   * @param string $inputFilename
   * @param string $filename
   */
  public function __construct($inputFilename = 'in.txt', $filename = 'out.txt')
  {
    $this->filename = $filename;
    $this->inputFilename = $inputFilename;

    $this->setDataArray();
    $num = $this->findGroups();
    $this->writeNum($num);
  }

  //считывание входных данных
  private function setDataArray()
  {
    $this->dataArray = array();

    $handle = @fopen(__DIR__.DIRECTORY_SEPARATOR.$this->inputFilename, 'r');

    if ($handle) {
      $i = 1;
      while (false !== ($buffer = fgets($handle, 4096))) {
        $arr = explode(',', trim($buffer));
        foreach ($arr as &$val) {
          $val = 'B'.(int) $val;
          $this->dataArray[$val][] = 'A'.$i;
        }
        $this->dataArray['A'.$i] = $arr;
        ++$i;
      }
      if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
      }
      fclose($handle);
    }

    if (empty($this->dataArray)) {
      echo 'Error: no data in file '.__DIR__.DIRECTORY_SEPARATOR.$this->inputFilename;
    }
  }

  //поиск в глубину
  private function dfs($graph, $startNode)
  {
    $this->visited[] = $startNode;

    foreach ($graph[$startNode] as $index => $vertex) {
      if (!in_array($vertex, $this->visited, true)) {
        $this->dfs($graph, $vertex);
      }
    }
  }

  private function findStartNode()
  {
    foreach ($this->dataArray as $key => $tmp) {
      if (!in_array($key, $this->visited, true)) {
        return $key;
      }
    }
  }

  //поиск количества компонент связности
  private function findGroups()
  {
    $num = 0;
    while (count($this->visited) < count(array_keys($this->dataArray))) {
      ++$num;
      $start = $this->findStartNode();
      $this->dfs($this->dataArray, $start);
    }

    return $num - 1;
  }

  //записываем результат
  private function writeNum($num)
  {
    echo $num.'\n'/*.'</b></pre>'*/;

    $handle = @fopen(__DIR__.DIRECTORY_SEPARATOR.$this->filename, 'w');
    if ($handle) {
      @fwrite($handle, $num."\n");
      fclose($handle);
    }
  }
}

$start = microtime(true);

for ($i = 1; $i < 15; ++$i) {
  $input = 'graph_input'.DIRECTORY_SEPARATOR."input$i.txt";
  $output = 'graph_output'.DIRECTORY_SEPARATOR."output$i.txt";
  echo /*"<pre style=\"font-size:14pt;\">*/"Result $i:"/*:     <b>"*/;
  ${'a'.$i} = new MyGraph($input, $output);
}

$end = microtime(true);
$time = $end - $start;
echo $time;
