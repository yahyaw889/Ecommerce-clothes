.data
n1: .word 5
n2: .word 4
.text 
.globl go
go:
lw t1,n1 
lw t2,n2 
mul s0,t1,t2
nop

# .text
# .globl main
# add:
# addi a0, a1,5
# jalr t1, t0 ,0 #return from a
# main:
# li  a1 ,10
# jal t0, add #calling 
# nop

#---------
#multiple
#----------
#li x1,2
#li x2,5
#mul x3, x1 ,x2
#nop

#------------
#addition 
#--------------
#li x2,5
#li x1,2
#add x3, x1 ,x2
#nop
#============
#li a1,12
#addi a0,a1,5
#nop
#-------------- 