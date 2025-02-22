#!/usr/bin/python3
import sys
if(len(sys.argv) != 3) :
  print ("Usage: manusplit.py infile Manufacturer")
  sys.exit(-1)

input_name = sys.argv[1]
Thisman = sys.argv[2]
dosupplier = False
mylines = []
with open(input_name,"r") as fi:
    for line in fi:
        line = line.rstrip()
        mylines.append(line)
        if(dosupplier):
          supplier = line
          dosupplier = False
          continue
        if(line[0:12] == ">  <SUPPLIER"):
          dosupplier = True
        if(line == '$$$$'):
          if(supplier == Thisman):
            for t in mylines:
              print (t)
          mylines = []

