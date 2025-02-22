#!/usr/bin/python3
import sys
# get sys package for file arguments etc
from getpass import getpass # securely take users pass without displaying it on the screen
import pymysql # Lib to connect and interact with mysql database

if(len(sys.argv) != 2) :
  print("Usage: Compounds_tablepop.py infile")
  sys.exit(-1)

user=input("Please enter your user name\n")
passwd=getpass("Please enter your password\n")
dbname=input("Please enter the FULL database name\n")

# Connect to the provided database
con = pymysql.connect(host='127.0.0.1', user=user, passwd=passwd, db=dbname)
cur = con.cursor()

input_name = sys.argv[1]

# Flags to track the state of the parser
title = True # extracts the title of the compound
dosupplier = False # Identifies and extracts the supplier name
doprops = False # Extracts the molecular properties

with open(input_name,"r") as input_file:
    for line in input_file:
        line = line.rstrip()
        #print line
        if(title):
          this_title = line
          title = False
          continue
        if(dosupplier):
          supplier = line
          dosupplier = False
          continue
        if(doprops) :
          if(len(line) < 3) :
            doprops = False
            continue
          else :
            props = line.split()
            if(props[0] == 'MW'):
              mw = props[2]
            if(props[0] == 'nCIC'):
              ncic = props[2]
            if(props[0] == 'RBN'):
              rbn = props[2]
            if(props[0] == 'nHDon'):
              nhd = props[2]
            if(props[0] == 'nHAcc'):
              nha = props[2]
            if(props[0] == 'TPSA'):
              TPSA = props[2]
            if(props[0] == 'XLogP'):
              XLogP = props[2]
        if(line == '$$$$'):
          title = True
          print(this_title," ",supplier," ",mw," ",ncic," ",rbn," ",nhd," ",nha," ",TPSA," ",XLogP,"\n")
          # query manufacturers table for ID corresponding to the extracted supplier name
          sql = "SELECT id FROM Manufacturers WHERE name='%s'" % (supplier)
          cur.execute(sql)
          row = cur.fetchone()
          supid = row[0]
          # insert compound data to mysql
          sql = "INSERT INTO Compounds (ncycl,nhdon,nhacc,nrotb,ManuID,catn,mw,TPSA,XLogP) values (%s,%s,%s,%s,%s,'%s',%s,%s,%s)" % (ncic,nhd,nha,rbn,supid,this_title,mw,TPSA,XLogP)
          print(sql,"\n")
          cur.execute(sql)
        if(line[0:12] == ">  <SUPPLIER"):
          dosupplier = True
        if(line[0:13] == ">  <molecular"):
          doprops = True
con.commit() # commits all changes to the database
con.close() # closes the connection


