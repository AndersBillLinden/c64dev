<?
if (count($argv) != 3)
	die("Syntax: $argv[0] number_of_bytes random.bin");
	
$num_bytes = $argv[1];
$outfile = $argv[2];

$result = '';

for ($i = 0; $i < $num_bytes; $i++)
	$result .= chr(rand(0,255));
	
file_put_contents($outfile, $result);
?>
