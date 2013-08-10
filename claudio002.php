<?php

parsefileIntoMemory();



if(isset($argv[1])) {
	if ($handle = opendir($argv[1])) {
	    echo "Directory handle: $handle\n";
	    echo "Entries:\n";
	
	    /* This is the correct way to loop over the directory. */
	    while (false !== ($entry = readdir($handle))) {
	    	if(preg_match("!(\.xls|\.csv)$!i", $entry)){
		    	print "{$entry}\n";	
		    	$dest = "{$argv[1]}/" 
		    		. preg_replace("!\.!", ".new.", $entry);
		    	$src = "{$argv[1]}/{$entry}";
		    	process($src,$dest);
	    	}
	    }
	}
}



function process($in,$out)
{

	$fp = fopen($in, "rw");
	$newFp = fopen($out, "w");

	$patternOne = "(^K\.|K\.$|K[^\.])";
	///false cases
	$patternTwo = "(\..+(K[^\[]).+\.)";
	//true case COUNT THESE
	$patternThree = "(K\[170\.11\])";
	$patternFour = "K\[170\.11\]\.";


	$tally = array();

	$inc = 0;

	while (($data = fgetcsv($fp, 1000, "\t")) !== FALSE) {

		$inc++;

		list($a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k) = $data;

		if(array_key_exists($e, $tally)){
			print "hhhhiiii {$e}";
			continue;
		}

		$tally[$e] = 1;

		if(preg_match("!{$patternOne}!", $e, $matches, PREG_OFFSET_CAPTURE)){
			$h = 1;
			//look up in database and find position of k.xxxxxx
			
			
		}

		fwrite($newFp, "$a\t$b\t$c\t$d\t$e\t$f\t$g\t$h\t$i\t$j\t$k\n");

	}
}



function parsefileIntoMemory(){
	
  $fp = fopen("/Users/nathantrujillo/Downloads/Yeast.RS.fasta", "r");
  $dataStore = array();
  
  while(($line = fgets($fp, 4096)) !== false){  	
  	if(preg_match("!^>!", $line)) { //new entry	  		
  		print "{$line}";
  		list($sysName, $geneName) = explode(" ", $line);
  		$sysName = preg_replace("!^>!", "", $sysName);
  		$dataStore[$sysName] = "";  		  		
  	}	  		
    if(!preg_match("!^>!", $line)){
  		$dataStore[$sysName] .= trim($line);
    }
  }
  print_r($dataStore);
}



function findPatternInFile($pattern, $orf = null){
	
	
	
	
}







