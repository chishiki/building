<?php

final class BuildingAPI {
		
	    private $loc;
	    private $input;
	    
	    public function __construct($loc, $input) {
			
	        $this->loc = $loc;
	        $this->input = $input;
			
		}
		
		public function response() {

            $response = '{"api":"building"}';
            return $response;

		}
		
	}

?>