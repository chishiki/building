<?php 

final class BuildingIndexView {

    private $urlArray;
	private $view;
	
	public function __construct($urlArray) {
		
	    $this->urlArray = $urlArray;
		$this->view = $this->index();

	}

	private function index() {

		$h = '';
		return $h;
	    
	}
	
	public function getView() {
		
		return $this->view;
		
	}
	
}


?>