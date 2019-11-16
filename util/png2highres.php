<?
if (count($argv) != 3)
	die("Syntax: $argv[0] highres_320x200.png highres_320x200.bin");

$infile = $argv[1];
$outfile = $argv[2];
	
$png = imagecreatefrompng($infile);

$result = '';

$width = imagesx($png);
$height = imagesy($png);

if ($width != 320 || $height != 200)
	die("The image must be 320x200");

$num_sprites_in_width = floor(($width - 1)/25);
$num_sprites_in_height = floor(($height - 1)/22);

for ($y = 0; $y < 25; $y++)
	for ($x = 0; $x < 40; $x++)
		$result .= pull_image($png, $x, $y);

file_put_contents($outfile, $result);

function pixel_is_black($png, $x, $y)
{
	$pixel = imagecolorat($png, $x, $y);

	return $pixel == 0;
}

function pull_image($png, $x, $y)
{
	$x_pix = $x * 8;
	$y_pix = $y * 8;

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
