<?
if (count($argv) != 3)
	die("Syntax: $argv[0] sprites.png sprites.bin");

$infile = $argv[1];
$outfile = $argv[2];
	
$png = imagecreatefrompng($infile);

$result = '';

$width = imagesx($png);
$height = imagesy($png);

$num_sprites_in_width = floor(($width - 1)/25);
$num_sprites_in_height = floor(($height - 1)/22);

for ($y = 0; $y < $num_sprites_in_height; $y++)
	for ($x = 0; $x < $num_sprites_in_width; $x++)
		if (!image_is_blank($png, $x, $y))
			$result .= pull_image($png, $x, $y) . chr(0);

file_put_contents($outfile, $result);

function image_is_blank($png, $x, $y)
{
	$x_pix = 1 + $x * 25;
	$y_pix = 1 + $y * 22;

	return area_is_black($png, $x_pix, $y_pix);
}

function area_is_black($png, $x_pix, $y_pix)
{
	for ($y = 0; $y < 21; $y++)
		for ($x = 0; $x < 24; $x++)
			if (!pixel_is_black($png, $x_pix + $x, $y_pix + $y))
				return false;

	return true;
}

function pixel_is_black($png, $x, $y)
{
	$pixel = imagecolorat($png, $x, $y);

	return $pixel == 0;
}

function pull_image($png, $x, $y)
{
	$x_pix = 1 + $x * 25;
	$y_pix = 1 + $y * 22;

	return pull_area($png, $x_pix, $y_pix);
}

function pull_area($png, $x_pix, $y_pix)
{
	$result = '';

	for ($y = 0; $y < 21; $y++)
	{
		$result .= collect_8_pixels($png, $x_pix, $y_pix + $y)
			. collect_8_pixels($png, $x_pix + 8, $y_pix + $y)
			. collect_8_pixels($png, $x_pix + 16, $y_pix + $y);
	}

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
