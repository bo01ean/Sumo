<?php






class Parser002 {

	public $dataStore = array();

	
	public function __construct()
	{
		global $argv;
		
		$this->parsefileIntoMemory();
		if(isset($argv[1])) {
			if ($handle = opendir($argv[1])) {
				echo "Entries:\n";
			
			    /* This is the correct way to loop over the directory. */
			    while (false !== ($entry = readdir($handle))) {
			    	if(preg_match("!(\.xls|\.csv)$!i", $entry, $matches)){
				    	print "{$entry}\n";	
				    	$dest = "{$argv[1]}/" 
				    		. preg_replace("!" .  $matches[1] ."!", ".out", $entry);
				    	$src = "{$argv[1]}/{$entry}";
				    	$this->process($src,$dest);
			    	}
			    }
			}
		}
	}




	public function process($in, $out)
	{
		print "Working on {$in} --> {$out}\n";
		
		$fp = fopen($in, "rw");
		$newFp = fopen($out, "w");
	
		$patternOne = "(^K\.|K\.[A-Z]$|^K\.[[\w\[\]\.]]+K\.[A-Z]$)";
	
		$tally = array();
	
		$inc = 0;
	
		while (($data = fgetcsv($fp, 1000, "\t")) !== FALSE) {
	
			$inc++;
			$matchPatternOne = 0;
			$position = null;			
			
			list($a,$b,$c,$d,$e,$f,$g) = $data;
			
			if(array_key_exists($e, $tally)){
				continue;
			}
	
			$tally[$e] = 1;
	
			if(preg_match_all("!{$patternOne}!", $e, $matches)){
				
				//print "{$e}\n" . print_r($matches , true) . "\n";
				
				$matchPatternOne = 1;
				$lookup = array();

				if(preg_match("!\.([\w\[\]\.]+)\.!i", $e, $lookup)) {
					//print "{$e} Matches!!\n";
//					print " --->> " . print_r($lookup[1], true) . "\n";				/
//					print "Looking up:\n";
					
					//look up in database and find position of k.xxxxxx					
					$position = $this->lookupPartial($f, preg_replace("![\.\[\]0-9]!", "", $lookup[1]));
				}
							
				
				
			}
			
			if ($matchPatternOne) {
				
				$data[] = $matchPatternOne;
				$data[] = join(",", $position);
				
				$writeOut = join("\t", $data) . "\n";
				
				fwrite($newFp, $writeOut);
			}	
		}
	}



	public function lookupPartial($key, $e) {
			
			print "\t\tSEARCH::{$e}\n";
			
			$found = array();
			if (preg_match("!($e)!im", $this->dataStore[$key], $found, PREG_OFFSET_CAPTURE)) {
				//print "bammmm\n";
			//	print "{$this->dataStore[$key]}\n";
				
				// get k positions in search
				$positions = array();
				
				$tmp = array();				
				$tmp[] = $found[1][1];
				
				if(preg_match_all("!([k])!i", $e, $positions, PREG_OFFSET_CAPTURE)) {
					foreach($positions[1] as $k => $pos) {
						if($k == 0 || $k == count($positions[1][$k]) ){
							$tmp[] = ($pos[1] + $found[1][1]);
						}
					}	
				}				
				return $tmp;
			}
	}
















	public function parsefileIntoMemory(){
		
	  $fp = fopen("Yeast.RS.fasta", "r");
	  
	  while(($line = fgets($fp, 4096)) !== false){  	
	  	if(preg_match("!^>!", $line)) { //new entry	  		
	  		//print "{$line}";
	  		list($sysName, $geneName) = explode(" ", $line);
	  		$sysName = preg_replace("!^>!", "", $sysName);
	  		$this->dataStore[$sysName] = "";  		  		
	  	}	  		
	    if(!preg_match("!^>!", $line)){
	  		$this->dataStore[$sysName] .= trim($line);
	    }
	  }
	}
}


$clajda = new Parser002();






