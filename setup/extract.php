<?php
$lines = file('index.php');
$out = "<?php\n";
for($i=4; $i<=339; $i++) {
    $out .= $lines[$i];
}
file_put_contents('templates/modern/index.php', $out);
