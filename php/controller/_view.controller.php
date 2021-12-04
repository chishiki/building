<?php

final class BuildingViewController {

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
		$this->messages = $messages;

	}

	public function getView() {

		$loc = $this->loc;
		$input = $this->input;
		$modules = $this->modules;
		$errors = $this->errors;
		$messages = $this->messages;

		if ($loc[0] == 'building') {
			if ($loc[1] == 'admin') {
				if ($loc[2] == 'buildings') { $v = new BuildingView($loc, $input, $modules, $errors, $messages); }
				if ($loc[2] == 'units') { $v = new BuildingUnitsView($loc, $input, $modules, $errors, $messages); }
				if ($loc[2] == 'facilities') { $v = new BuildingFacilitiesView($loc, $input, $modules, $errors, $messages); }
				if ($loc[2] == 'reservations') { $v = new BuildingReservationsView($loc, $input, $modules, $errors, $messages); }
				if ($loc[2] == 'users') { $v = new BuildingUsersView($loc, $input, $modules, $errors, $messages); }
			}
			if ($loc[1] == 'resident') {
				if ($loc[2] == 'reservations') { $v = new BuildingReservationsView($loc, $input, $modules, $errors, $messages); }
				if ($loc[2] == 'maintenance') { $v = new BuildingMaintenanceView($loc, $input, $modules, $errors, $messages); }
			}
		}

		if (isset($v)) {
			return $v->getView();
		} else {
			$url = '/' . Lang::prefix();
			header("Location: $url" );
		}

	}

}

?>