<?php
function get_answer($fileName = "input.txt") {
    //$fileName = "input.txt";
    $f = @fopen(__DIR__ . DIRECTORY_SEPARATOR . $fileName, "r");

    if(!$f)
        die("Could not open file: " . $fileName);

    $sections = [];
    $tags = [];

    $unvisitedSect = [];
    $unvisitedTags = [];

    $currSect = 1;
    while($line = fgets($f)) {
        $sections[] = array_map('trim', explode(',', $line));
        foreach(end($sections) as $tag) {
            $unvisitedTags[$tag] = $tag;

            if(!isset($tags[$tag]))
                $tags[$tag] = [];
            
            if(!in_array($currSect, $tags[$tag])) {
                array_push($tags[$tag], $currSect);
            }
        }

        $unvisitedSect[$currSect] = $currSect;

        $currSect++;
    }
    
    /*
    print_r($sections);
    echo "<br>";
    print_r($tags);
    echo "<br>";
    */

    $pockets = [];
    while(count($unvisitedSect)) {
        $pockets[][] = reset($unvisitedSect);
        foreach($pockets as &$pocket) {
            for($i = 0; $i < count($pocket); $i++) {
                $sect = $pocket[$i];
                if(!in_array($sect, $unvisitedSect)) 
                    continue;
                
                foreach($sections[$sect-1] as $tag) {
                    foreach($tags[$tag] as $el) 
                        if(!in_array($el, $pocket))
                            $pocket[] = $el;

                    unset($unvisitedTags[$tag]);
                }

                unset($unvisitedSect[$sect]);
            }
        }
    }

    /*echo "{<br>";
    foreach($pockets as $p) {
        print_r($p);
        echo "<br>";
    }
    echo "}";

    echo "<br><br>";*/
    echo "ANSWER: <b>" . (count($pockets) - 1) . "</b>";
}
$start = microtime(true);

for ($i = 1; $i < 15; ++$i) {
  $input = 'graph_input'.DIRECTORY_SEPARATOR."input$i.txt";
  //$output = 'my_graph_output'.DIRECTORY_SEPARATOR."output$i.txt";
  echo "<pre style=\"font-size:14pt;\">Результат #$i:     ";
  get_answer($input);
}

$end = microtime(true);
$time = $end - $start;
echo "<br>Скрипт выполнен за $time секунд.";

?>