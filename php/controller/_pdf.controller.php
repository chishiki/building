<?php

final class BuildingPDF {

	private $doc;
	private $fileObject;
	private $fileObjectID;

	public function __construct($loc, $input) {

		if ($loc[2] == 'products' && is_numeric($loc[3])) {

			// /pdf/building/products/<productID>/

			$buildingID = $loc[3];
			$doc = 'BUILDING EXAMPLE PDF';
			$fileObject = 'Building';
			$fileObjectID = $buildingID;

		}

		$this->doc = $doc;
		$this->fileObject = $fileObject;
		$this->fileObjectID = $fileObjectID;

	}

	public function doc() {

		return $this->doc;

	}

	public function getFileObject() {

		return $this->fileObject;

	}

	public function getFileObjectID() {

		return $this->fileObjectID;

	}

}

?>