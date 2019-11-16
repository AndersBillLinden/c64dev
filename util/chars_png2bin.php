<?
if (count($argv) != 4)
	die("Syntax: $argv[0] chars.png num_chars_to_pull chars.bin");

$infile = $argv[1];
$num_chars_to_pull = $argv[2];
$outfile = $argv[3];

$num = 0;

$png = imagecreatefrompng($infile);

$result = '';

$width = imagesx($png);
$height = imagesy($png);

if ($width != 145)
	die("input png must have width 145");

if ($height != 145)
	die("input png must have height 145");	

for ($y = 0; $y < 16; $y++)
	for ($x = 0; $x < 16 && $num++ < $num_chars_to_pull; $x++)
		$result .= pull_image($png, $x, $y);

file_put_contents($outfile, $result);

function pixel_is_black($png, $x, $y)
{
	$pixel = imagecolorat($png, $x, $y);

	return $pixel == 0;
}

function pull_image($png, $x, $y)
{
	$x_pix = 1 + $x * 9;
	$y_pix = 1 + $y * 9;

	return pull_area($png, $x_pix, $y_pix);
}

function pull_area($png, $x_pix, $y_pix)
{
	$result = '';

	for ($y = 0; $y < 8; $y++)
		$result .= collect_8_pixels($png, $x_pix, $y_pix + $y);

	return $result;
}

function collect_8_pixels($png, $x_pix, $y_pix)
{
	$result = 0;

	for ($x = 0; $x < 8; $x++)
	{
		$result <<= 1;

		if (!pixel_is_black($png, $x_pix + $x, $y_pix))
			$result |= 1;
	}

	return chr($result);
}
?>
