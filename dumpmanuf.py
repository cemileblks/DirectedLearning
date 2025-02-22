#!/usr/bin/python3
import sys
if(len(sys.argv) != 2) :
  print("Usage: dumpmanuf.py infile ")
  sys.exit(-1)

input_name = sys.argv[1]
dosupplier = False
with open(input_name,"r") as fi:
    for line in fi:
        line = line.rstrip()
        if(dosupplier):
          supplier = line
          dosupplier = False
          print (supplier)
          continue
        if(line[0:12] == ">  <SUPPLIER"):
          dosupplier = True
