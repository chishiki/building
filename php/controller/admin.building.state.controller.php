<?php

final class AdminBuildingStateController {

	private $loc;
	private $input;
	private $modules;
	private $errors;
	private $messages;

	public function __construct($loc, $input, $modules) {

		$this->loc = $loc;
		$this->input = $input;
		$this->modules = $modules;
		$this->errors = array();
		$this->messages =  array();

		// let's only allow logged in users to view building pages
		if (!Auth::isLoggedIn()) {
			$loginURL = '/' . Lang::prefix() . 'login/';
			header("Location: $loginURL");
		}

	}

	public function setState() {

		$loc = $this->loc;
		$input = $this->input;

		if ($loc[2] == 'buildings') {

			// /building/admin/buildings/create/
			if ($loc[3] == 'create' && isset($input['building-create'])) {

				// $this->errors (add validation here: ok to create?)
				// $this->errors[] = array('building-create' => Lang::getLang('thereWasAProblemCreatingYourBuilding'));

				if (empty($this->errors)) {

					$building = new Building();
					foreach ($input AS $property => $value) { if (isset($building->$property)) { $building->$property = $value; } }
					Building::insert($building, false, 'building_');
					$successURL = '/' . Lang::prefix() . 'building/admin/buildings/';
					header("Location: $successURL");

				}

			}

			// /building/admin/buildings/update/<buildingID>/
			if ($loc[3] == 'update' && ctype_digit($loc[4]) && isset($input['building-update'])) {

				$buildingID = $loc[4];

				// $this->errors (add validation here: ok to update?)
				// $this->errors[] = array('building-update' => Lang::getLang('thereWasAProblemUpdatingYourBuilding'));

				if (empty($this->errors)) {

					$building = new Building($buildingID);
					$building->updated = date('Y-m-d H:i:s');
					foreach ($input AS $property => $value) { if (isset($building->$property)) { $building->$property = $value; } }
					$conditions = array('buildingID' => $buildingID);
					Building::update($building, $conditions, true, false, 'building_');
					$this->messages[] = Lang::getLang('buildingUpdateSuccessful');

				}

			}

			// /building/admin/buildings/update/<buildingID>/images/
			if ($loc[3] == 'update' && ctype_digit($loc[4]) && $loc[5] == 'images' && isset($input['submitted-images'])) {

				$buildingID = $loc[4];
				// $this->errors (add validation here: ok to upload?)
				// $this->errors[] = array('building-update' => Lang::getLang('thereWasAProblemAddingYourBuildingImages'));
				Image::uploadImages($_FILES['images-to-upload'], 'Building', $buildingID, false);

			}

			// /building/admin/buildings/update/<buildingID>/files/
			if ($loc[3] == 'update' && ctype_digit($loc[4]) && $loc[5] == 'files' && isset($input['submitted-files'])) {

				$buildingID = $loc[4];
				// $this->errors (add validation here: ok to upload?)
				// $this->errors[] = array('building-update' => Lang::getLang('thereWasAProblemAddingYourBuildingFiles'));
				File::uploadFiles($_FILES['files-to-upload'], 'Building', $buildingID, $input['fileTitleEnglish'], $input['fileTitleJapanese']);

			}

			// /building/admin/buildings/
			if (isset($input['building-confirm-delete'])) {

				$buildingID = $input['buildingID'];

				if ($input['buildingID'] != $buildingID) {
					$this->errors[] = array('building-delete' => Lang::getLang('thereWasAProblemDeletingYourBuilding'));
				}

				if (empty($this->errors)) {

					$building = new Building($buildingID);
					$building->markAsDeleted();
					$this->messages[] = Lang::getLang('buildingDeleteSuccessful');

				}

			}

		}

	}

	public function getErrors() {
		return $this->errors;
	}

	public function getMessages() {
		return $this->messages;
	}

}

?>