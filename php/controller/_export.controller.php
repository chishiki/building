<?php

final class BuildingExportController {

	private $loc;
	private $input;
	private $modules;
	
	private $filename;
	private $columns;
	private $rows;

	public function __construct($loc, $input, $modules) {

		$this->loc = $loc;
		$this->input = $input;
		$this->modules = $modules;

		$this->filename = 'export';
		$this->columns = array();
		$this->rows = array();
		
		if ($loc[0] == 'csv' && $loc[1] == 'building') {

			if ($loc[2] == 'buildings') {

				// /csv/building/buildings/

				$arg = new BuildingListParameter();
				$buildingList = new BuildingList($arg);
				$buildings = $buildingList->buildings();

				$this->filename = 'building_export_' . str_replace('_', '-', $loc[4]);

				$this->columns[] = 'buildingID';
				$this->columns[] = 'buildingName';

				foreach ($buildings AS $buildingID) {
					$data = array();
					$building = new Building($buildingID);
					$data[] = $buildingID;
					$data[] = $building->buildingName;
					$this->rows[] = $data;
				}

			}

		}

	}

	public function filename() {

		return $this->filename;
		
	}
	
	public function columns() {

		return $this->columns;
		
	}
	
	public function rows() {

		return $this->rows;
		
	}

}

?>