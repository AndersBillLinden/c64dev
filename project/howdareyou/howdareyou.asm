!to "howdareyou.prg",cbm

* = $0800

sysline:
!byte $00,$0b,$08,$01,$00,$9e,$32,$30,$36,$31,$00,$00,$00 ;= SYS 2061

* = $080d
		
	sei
	lda # < wave_data
	ldx # > wave_data
	sta loop_start + 1
	stx loop_start + 2
	
loop_start:
                            ; cycles nibble 1: 3 (from jmp in end of loop)
	lda $0000
	
	tax                       ; cycles nibble 1: 7
	
	lsr                       ; cycles nibble 1: 9
	lsr                       ; cycles nibble 1: 11
	lsr                       ; cycles nibble 1: 13
	lsr                       ; cycles nibble 1: 15
	
	tay                       ; cycles nibble 1: 17
	
	
	bit $ea                   ; cycles nibble 2: 20
	
  !for i, 1, 8 {	
		nop
	}                         ; cycles nibble 1: 20 + 8 * 2 = 36
	
	tya                       ; cycles nibble 1: 38
	
	jsr delay
	sta $d418
		
	inc loop_start + 1        ; cycles nibble 2: 6
	bne over
	                          ; cycles nibble 2: 8
	inc loop_start + 2        ; cycles nibble 2: 14
	jmp over3
over:	
														; cycles nibble 2: 9
	nop
	nop
	nop
	nop
over3:
	                          ; cycles nibble 2: 17
	
	lda loop_start + 1        ; cycles nibble 2: 21
	cmp # < wave_data_end     ; cycles nibble 2: 23
	bne over2
	                          ; cycles nibble 2: 25
	lda loop_start + 2        ; cycles nibble 2: 29
	cmp # > wave_data_end     ; cycles nibble 2: 31
	bne over4
                            ; cycles nibble 2: 33
	jmp theend
	
over2:
                            ; cycles nibble 2: 26
  !for i, 1, 4 {	
		nop
	}                         ; cycles nibble 1: 26 + 4 * 2 = 34														
over4:
                            ; cycles nibble 2: 34
	txa                       ; cycles nibble 2: 36
	and #$0f                  ; cycles nibble 2: 38							  

  jsr delay
	sta $d418
	
	jmp loop_start

theend:
	cli
	rts

delay:
  !for i, 1, 30 {	
		nop
	}
	rts

wave_data:
!bin "howdareyou.wav.bin"
wave_data_end: