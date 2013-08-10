Sumo 
====
some protein parsers for cleave offset detection from mass spec output Ac200





parser001.php
=============
Parse csv formatted protein stuff an remove duplicates

How to run:
=============
php parser001.php .


parser002.php
=============
Parse Yeast.RS.fasta into memory hash.
Look for K.|.K[A-Z]|K.*.K[A-Z] patterns in mass spec file, and look up k positions from mass spec string
Output results into .out file

How to run:
=============
php parser002.php .

