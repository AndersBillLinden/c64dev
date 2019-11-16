!to "howdareyou.prg",cbm

* = $0800

sysline:
!byte $00,$0b,$08,$01,$00,$9e,$32,$30,$36,$31,$00,$00,$00 ;= SYS 2061

* = $080d

	;lda #%00100000 ; hires
	;sta $d011
	
	lda #1
	sta $0400 ; put a on screen
	
	sei
backback:
	lda #0
	ldx #20
back1:
	cpx $D012
	bne back1
	sta $d020
	sta $d021
	lda #1
	ldx #30
back2:
	cpx $D012
	bne back2
	sta $d020
	sta $d021
jmp backback


; 53265,48 (32+16)  DO11,30
; 53272,21 (default)          D018
;       23 (små bokstäver)