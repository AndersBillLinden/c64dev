<?
if (count($argv) != 3)
	die("Syntax: $argv[0] infile.bin outfile.bin");

$infile = $argv[1];
$outfile = $argv[2];

$input = file_get_contents($infile);
$result = compress($input);
file_put_contents($outfile, $result);

function compress($input)
{
	$count = 0;
	$last = 0;

	$result = '';

	$in_length = strlen($input);
	
	for ($i = 0; $i < $in_length; $i++)
	{
		$byte = ord($input[$i]);
		
		if ($count > 0 && $byte == $last)
		{
			$count++;
			
			if ($count == 255)
			{
				$result .= chr(255) . chr($last);
				$count = 0;
			}
		}
		else
		{
			if ($count > 0)
				$result .= chr($count) . chr($last);

			$last = $byte;
			$count = 1;
		}
	}

	if ($count > 0)
			$result .=  chr($count). chr($last);
			
	return $result;
}
?>
