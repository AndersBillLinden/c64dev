<?
if (count($argv) != 3)
	die("Syntax: $argv[0] input.wav output.bin");

$filename = $argv[1];

$outfile = fopen($argv[2], "wb");

$buffer = file_get_contents($filename);
$buffer_length = strlen($buffer);

$signature = get_string(0, 4);

$output_byte = 0;
$output_byte_bit_count = 0;

if ($signature != "RIFF")
	die("Not a wave file");

$chunk_length = get_int32(4);

$wave_type = get_string(0, 4);

if ($wave_type != "RIFF")
	die("Unsupported WAVE type, expected RIFF, got " . $wave_type);

$offset = 12;
while ($offset < $buffer_length)
{
	$chunk_type = get_string($offset, 4);
	$chunk_length = get_int32($offset + 4);
	if ($chunk_type == "fmt ")
	{
		$compression = get_int16($offset + 8);		
		if ($compression != 1)
			die ("No compression support");
		
		$channels = get_int16($offset + 10);
		if ($channels != 1)
			die ("No multiple channel support");

		$samples_per_second = get_int32($offset + 12);
		$bits_per_sample = get_int16($offset + 22);
	}
	else if ($chunk_type == "data")
	{
		$waveform_buffer_length = $chunk_length - 8;
		$waveform_buffer_offset = $offset + 8;
	}
	
	$offset += 8 + $chunk_length;
}

$sample_size = $bits_per_sample / 8;
$num_samples = $waveform_buffer_length / $sample_size;

$output_samples_per_second = 8000;

$offset = $waveform_buffer_offset;
$output_sample_num = -1;
for ($i = 0; $i < $num_samples; $i++)
{
	$sample = ord($buffer[$offset]);
	$time = $i / $samples_per_second;
	$output_sample_by_time = floor($time * $output_samples_per_second);
	
	if ($output_sample_by_time > $output_sample_num)
	{
		output_top_bits($sample, 4);		
		$output_sample_num = $output_sample_by_time;
	}
	
	$offset += $sample_size;
}

function get_string($offset, $length)
{
	global $buffer;
	$result = "";
	
	for ($i = 0; $i < $length; $i++)
		$result .= $buffer[$offset + $i];
	
	return $result;
}

function get_int16($offset)
{
	global $buffer;
	$ar = $buffer;

	return (ord($ar[$offset + 1])<<8)
		+ ord($ar[$offset]);
}

function get_int32($offset)
{
	global $buffer;
	$ar = $buffer;

	return (ord($ar[$offset + 3])<<24)
		+ (ord($ar[$offset + 2])<<16)
		+ (ord($ar[$offset + 1])<<8)
		+ ord($ar[$offset]);
}

function println($str)
{
	echo $str . "\n";
}

function output_top_bits($sample, $bits)
{
	global $output_byte;
	global $output_byte_bit_count;
	global $outfile;
	
	for ($i = 0; $i < $bits; $i++)
	{
		$output_byte <<= 1;
		$output_byte |= ($sample & 128) != 0 ? 1 : 0;
		$sample <<= 1;
		
		if (++$output_byte_bit_count == 8)
		{
			fputs($outfile, chr($output_byte));
			$output_byte_bit_count = 0;
			$output_byte = 0;
		}
	}
}

?>