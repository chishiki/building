<?php

final class BuildingController {

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

	}

	public function setState() {

		$loc = $this->loc;
		$input = $this->input;
		$modules = $this->modules;

		if ($loc[0] == 'building') {
			
			if (!Auth::isLoggedIn()) {
				$loginURL = '/' . Lang::prefix() . 'login/';
				header("Location: $loginURL");
			}
			
			if ($loc[1] == 'admin') {
				if ($loc[2] == 'buildings') { $controller = new AdminBuildingStateController($loc, $input, $modules); }
				if ($loc[2] == 'units') { $controller = new AdminBuildingUnitStateController($loc, $input, $modules); }
				if ($loc[2] == 'facilities') { $controller = new AdminFacilityStateController($loc, $input, $modules); }
				if ($loc[2] == 'reservations') { $controller = new AdminReservationStateController($loc, $input, $modules); }
				if ($loc[2] == 'users') { $controller = new AdminUserStateController($loc, $input, $modules); }
			}
			
			if ($loc[1] == 'resident') {
				if ($loc[2] == 'reservations') { $controller = new ResidentReservationStateController($loc, $input, $modules); }
				if ($loc[2] == 'maintenance') { $controller = new ResidenMaintenanceStateController($loc, $input, $modules); }
			}
			
		}

		if (isset($controller)) {
			$controller->setState();
			$this->errors = $controller->getErrors();
			$this->messages = $controller->getMessages();
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