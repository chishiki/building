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
				if ($loc[2] == 'buildings') { $v = new AdminBuildingViewController($loc, $input, $modules, $errors, $messages); }
				if ($loc[2] == 'units') { $v = new AdminUnitViewController($loc, $input, $modules, $errors, $messages); }
				if ($loc[2] == 'facilities') { $v = new AdminFacilityViewController($loc, $input, $modules, $errors, $messages); }
				if ($loc[2] == 'reservations') { $v = new AdminReservationViewController($loc, $input, $modules, $errors, $messages); }
				if ($loc[2] == 'users') { $v = new AdminUserViewController($loc, $input, $modules, $errors, $messages); }
			}
			if ($loc[1] == 'resident') {
				if ($loc[2] == 'reservations') { $v = new ResidentReservationViewController($loc, $input, $modules, $errors, $messages); }
				if ($loc[2] == 'maintenance') { $v = new ResidentMaintenanceViewController($loc, $input, $modules, $errors, $messages); }
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