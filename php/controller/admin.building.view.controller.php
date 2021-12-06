<?php

final class AdminBuildingViewController {

	private $loc;
	private $input;
	private $modules;
	private $errors;
	private $messages;

	public function __construct($loc, $input, $modules, $errors, $messages) {

		$this->loc = $loc;
		$this->input = $input;
		$this->modules = $modules;
		$this->errors = $errors;
		$this->messages =  $messages;

	}

	public function getView() {

		$loc = $this->loc;
		$input = $this->input;
		$errors = $this->errors;

		if ($loc[2] == 'buildings') {

			$view = new BuildingView($loc, $input);

			// /building/admin/buildings/create/
			if ($loc[3] == 'create') { return $view->adminBuildingForm('create'); }

			// /building/admin/buildings/update/<buildingID>/
			if ($loc[3] == 'update' && ctype_digit($loc[4])) {

				$buildingID = $loc[4];

				if ($loc[5] == 'images') {

					$view = new ImageView($loc, $input, $errors);
					$building = new Building($buildingID);
					$hpv = new BuildingView();

					$arg = new NewImageViewParameters();
					$arg->cardHeader = $arg->cardHeader . ' [' . $building->buildingName . ']';
					$arg->navtabs = $hpv->adminBuildingFormTabs('update', $buildingID, 'images');
					$arg->cardContainerDivClasses = array('container');
					$arg->imageObject = 'Building';
					$arg->imageObjectID = $buildingID;
					$arg->displayDefaultRadio = true;

					return $view->newImageManager($arg);

				}

				if ($loc[5] == 'files') {

					$view = new FileView($loc, $input, $errors);
					$building = new Building($buildingID);
					$hpv = new BuildingView();

					$arg = new NewFileViewParameters();
					$arg->cardHeader = $arg->cardHeader . ' [' . $building->buildingName . ']';
					$arg->navtabs = $hpv->adminBuildingFormTabs('update', $buildingID, 'files');
					$arg->cardContainerDivClasses = array('container');
					$arg->fileObject = 'Building';
					$arg->fileObjectID = $buildingID;

					return $view->newFileManager($arg);

				}

				return $view->adminBuildingForm('update',$buildingID);

			}

			// /building/admin/buildings/confirm-delete/<buildingID>/
			if ($loc[3] == 'confirm-delete' && ctype_digit($loc[4])) {
				return $view->adminBuildingConfirmDelete($loc[4]);
			}

			// /building/admin/buildings/
			$arg = new BuildingListParameters();
			$arg->buildingPublished = null;
			$hpl = new BuildingList($arg);

			$arg->currentPage = 1;
			$arg->numberOfPages = ceil($hpl->buildingCount()/25);
			$arg->limit = 25;
			$arg->offset = 0;

			if (is_numeric($loc[3])) {
				$currentPage = $loc[3];
				$arg->currentPage = $currentPage;
				$arg->offset = 25 * ($currentPage- 1);
			}

			return $view->adminBuildingList($arg);

		}

	}

}

?>