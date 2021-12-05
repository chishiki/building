<?php

/*
CREATE TABLE `building_Building` (
    `buildingID` int(12) NOT NULL AUTO_INCREMENT,
    `siteID` int(12) NOT NULL,
    `creator` int(12) NOT NULL,
    `created` datetime NOT NULL,
    `updated` datetime NULL,
    `deleted` int(1) NOT NULL,
    `buildingName` varchar(255) NOT NULL,
    `buildingURL` varchar(255) NOT NULL,
    `buildingDescription` text NOT NULL,
    `buildingStartDate` date NULL,
    `buildingEndDate` date NULL,
    `buildingStatus` varchar(20) NOT NULL,
    `buildingPublished` int(1) NOT NULL,
    PRIMARY KEY (`buildingID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/

final class Building {
	
	public $buildingID;
	public $siteID;
	public $creator;
	public $created;
	public $updated;
	public $deleted;
	public $buildingName;
	public $buildingURL;
	public $buildingDescription;
	public $buildingStartDate;
	public $buildingEndDate;
	public $buildingStatus;
	public $buildingPublished;
	
	public function __construct($buildingID = null) {

		$dt = new DateTime();

		$this->buildingID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->creator = $_SESSION['userID'];
		$this->created = $dt->format('Y-m-d H:i:s');
		$this->updated = null;
		$this->deleted = 0;
		$this->buildingName = '';
		$this->buildingURL = '';
		$this->buildingDescription = '';
		$this->buildingStartDate = null;
		$this->buildingEndDate = null;
		$this->buildingStatus = '';
		$this->buildingPublished = 0;

		if ($buildingID) {

			$nucleus = Nucleus::getInstance();

			$wheres = array();

			$wheres[] = 'siteID = :siteID';
			$wheres[] = 'deleted = 0';
			$wheres[] = 'buildingID = :buildingID';

			$query = 'SELECT * FROM building_Building WHERE ' . implode(' AND ', $wheres) . ' LIMIT 1';

			$statement = $nucleus->database->prepare($query);
			$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
			$statement->bindParam(':buildingID', $buildingID, PDO::PARAM_INT);
			$statement->execute();

			if ($row = $statement->fetch()) {
				foreach ($row AS $key => $value) { if (property_exists($this, $key)) { $this->$key = $value; } }
			}

		}

	}
	
	public function markAsDeleted() {

		$dt = new DateTime();
		$this->updated = $dt->format('Y-m-d H:i:s');
		$this->deleted = 1;
		$conditions = array('buildingID' => $this->buildingID);
		self::update($this, $conditions, true, false, 'building_');

	}
	
}

final class BuildingList {

	private $buildings;

	public function __construct(BuildingListParameters $arg) {

		$this->buildings = array();

		// WHERE
		$wheres = array();
		$wheres[] = 'building_Building.deleted = 0';
		if (!is_null($arg->buildingID)) { $wheres[] = 'building_Building.creator = :creator'; }
		if (!is_null($arg->siteID)) { $wheres[] = 'building_Building.siteID = :siteID'; }
		if (!is_null($arg->creator)) { $wheres[] = 'building_Building.creator = :creator'; }
		if (!is_null($arg->buildingName)) { $wheres[] = 'building_Building.buildingName = :buildingName'; }
		if (!is_null($arg->buildingURL)) { $wheres[] = 'building_Building.buildingURL = :buildingURL'; }
		if (!is_null($arg->buildingStatus)) { $wheres[] = 'building_Building.buildingStatus = :buildingStatus'; }
		if ($arg->buildingPublished === true) { $wheres[] = 'building_Building.buildingPublished = 1'; }
		if ($arg->buildingPublished === false) { $wheres[] = 'building_Building.buildingPublished = 0'; }
		$where = ' WHERE ' . implode(' AND ',$wheres);

		// SELECTOR
		$selectorArray = array();
		foreach ($arg->resultSet AS $fieldAlias) { $selectorArray[] = $fieldAlias['field'] . ' AS ' . $fieldAlias['alias']; }
		$selector = implode(', ', $selectorArray);

		// ORDER BY
		$orderBys = array();
		foreach ($arg->orderBy AS $fieldSort) { $orderBys[] = $fieldSort['field'] . ' ' . $fieldSort['sort']; }
		$orderBy = '';
		if (!empty($orderBys)) { $orderBy = ' ORDER BY ' . implode(', ',$orderBys); }

		// BUILD QUERY
		$query = 'SELECT ' . $selector . ' FROM building_Building' . $where . $orderBy;
		if ($arg->limit) { $query .= ' LIMIT ' . ($arg->offset?$arg->offset.', ':'') . $arg->limit; }

		// PREPARE QUERY, BIND PARAMS, EXECUTE QUERY
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		if (!is_null($arg->buildingID)) { $statement->bindParam(':buildingID', $arg->buildingID, PDO::PARAM_INT); }
		if (!is_null($arg->siteID)) { $statement->bindParam(':siteID', $arg->siteID, PDO::PARAM_INT); }
		if (!is_null($arg->creator)) { $statement->bindParam(':creator', $arg->creator, PDO::PARAM_INT); }
		if (!is_null($arg->buildingName)) { $statement->bindParam(':buildingName', $arg->buildingName, PDO::PARAM_STR); }
		if (!is_null($arg->buildingURL)) { $statement->bindParam(':buildingURL', $arg->buildingURL, PDO::PARAM_STR); }
		if (!is_null($arg->buildingStatus)) { $statement->bindParam(':buildingStatus', $arg->buildingStatus, PDO::PARAM_STR); }
		$statement->execute();

		// WRITE QUERY RESULTS TO ARRAY
		while ($row = $statement->fetch()) { $this->buildings[] = $row; }

	}

	public function buildings() {

		return $this->buildings;

	}

	public function buildingCount() {

		return count($this->buildings);

	}

}

final class BuildingListParameters {

	// list filters
	public $buildingID;
	public $siteID;
	public $creator;
	public $buildingName;
	public $buildingURL;
	public $buildingStatus;
	public $buildingPublished;

	// view parameters
	public $currentPage;
	public $numberOfPages;

	// results, order, limit, offset
	public $resultSet;
	public $orderBy;
	public $limit;
	public $offset;

	public function __construct() {

		// list filters
		$this->buildingID = null;
		$this->siteID = $_SESSION['siteID'];
		$this->creator = null;
		$this->buildingName = null;
		$this->buildingURL = null;
		$this->buildingStatus = null;
		$this->buildingPublished = null; // [null => either; true => featured only; false => not featured only]

		// view parameters
		$this->currentPage = null;
		$this->numberOfPages = null;

		// results, order, limit, offset

		$this->resultSet = array();
		$building = new Building();
		foreach ($building AS $key => $value) {
			$this->resultSet[] = array('field' => 'building_Building.'.$key, 'alias' => $key);
		}

		$this->orderBy = array(
			array('field' => 'building_Building.created', 'sort' => 'DESC')
		);
		
		$this->limit = null;
		$this->offset = null;

	}

}

final class BuildingUtilities {

	public static function buildingUrlExists($buildingURL) {

		$arg = new BuildingListParameters();
		$arg->buildingURL = $buildingURL;
		$buildingList = new BuildingList($arg);

		if ($buildingList->buildingCount() > 0) {
			return true;
		} else {
			return false;
		}

	}

	public static function getBuildingWithURL($buildingURL) {

		$buildingID = null;

		$arg = new BuildingListParameters();
		$arg->buildingURL = $buildingURL;
		$buildingList = new BuildingList($arg);
		$buildings = $buildingList->buildings();

		if (count($buildings)) {
			$buildingID = $buildings[0]['buildingID'];
		}

		return $buildingID;

	}

}

?>