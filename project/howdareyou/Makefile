howdareyou.prg: howdareyou.asm howdareyou.wav.bin
	..\..\acme\acme howdareyou.asm

howdareyou.wav.bin: howdareyou.wav
	php ..\..\util\wav2bin.php howdareyou.wav howdareyou.wav.bin

decompile:
	../../emca/disassembler/emca howdareyou.prg > out.asm