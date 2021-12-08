<?php

/*
CREATE TABLE `building_Unit` (
    `unitID` int(12) NOT NULL AUTO_INCREMENT,
    `buildingID` int(12) NOT NULL,
    `siteID` int(12) NOT NULL,
    `creator` int(12) NOT NULL,
    `created` datetime NOT NULL,
    `updated` datetime NULL,
    `deleted` int(1) NOT NULL,
    `unitName` varchar(255) NOT NULL,
    `unitURL` varchar(255) NOT NULL,
    `unitDescription` text NOT NULL,
    `unitStartDate` date NULL,
    `unitEndDate` date NULL,
    `unitStatus` varchar(20) NOT NULL,
    `unitEnabled` int(1) NOT NULL,
    PRIMARY KEY (`unitID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/

final class Unit extends ORM {

	public $unitID;
	public $buildingID;
	public $siteID;
	public $creator;
	public $created;
	public $updated;
	public $deleted;
	public $unitName;
	public $unitURL;
	public $unitDescription;
	public $unitStartDate;
	public $unitEndDate;
	public $unitStatus;
	public $unitEnabled;

	public function __construct($unitID = null) {

		$dt = new DateTime();

		$this->unitID = 0;
		$this->buildingID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->creator = $_SESSION['userID'];
		$this->created = $dt->format('Y-m-d H:i:s');
		$this->updated = null;
		$this->deleted = 0;
		$this->unitName = '';
		$this->unitURL = '';
		$this->unitDescription = '';
		$this->unitStartDate = null;
		$this->unitEndDate = null;
		$this->unitStatus = '';
		$this->unitEnabled = 0;

		if ($unitID) {

			$nucleus = Nucleus::getInstance();

			$wheres = array();

			$wheres[] = 'deleted = 0';
			$wheres[] = 'unitID = :unitID';

			$query = 'SELECT * FROM building_Unit WHERE ' . implode(' AND ', $wheres) . ' LIMIT 1';

			$statement = $nucleus->database->prepare($query);
			$statement->bindParam(':unitID', $unitID, PDO::PARAM_INT);
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
		$conditions = array('unitID' => $this->unitID);
		self::update($this, $conditions, true, false, 'building_');

	}

}

final class UnitList {

	private $units;

	public function __construct(UnitListParameters $arg) {

		$this->units = array();

		// WHERE
		$wheres = array();
		$wheres[] = 'building_Unit.deleted = 0';
		if (!is_null($arg->unitID)) { $wheres[] = 'building_Unit.creator = :creator'; }
		if (!is_null($arg->buildingID)) { $wheres[] = 'building_Unit.buildingID = :buildingID'; }
		if (!is_null($arg->siteID)) { $wheres[] = 'building_Unit.siteID = :siteID'; }
		if (!is_null($arg->creator)) { $wheres[] = 'building_Unit.creator = :creator'; }
		if (!is_null($arg->unitName)) { $wheres[] = 'building_Unit.unitName = :unitName'; }
		if (!is_null($arg->unitURL)) { $wheres[] = 'building_Unit.unitURL = :unitURL'; }
		if (!is_null($arg->unitStatus)) { $wheres[] = 'building_Unit.unitStatus = :unitStatus'; }
		if ($arg->unitEnabled === true) { $wheres[] = 'building_Unit.unitEnabled = 1'; }
		if ($arg->unitEnabled === false) { $wheres[] = 'building_Unit.unitEnabled = 0'; }
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
		$query = 'SELECT ' . $selector . ' FROM building_Unit' . $where . $orderBy;
		if ($arg->limit) { $query .= ' LIMIT ' . ($arg->offset?$arg->offset.', ':'') . $arg->limit; }

		// PREPARE QUERY, BIND PARAMS, EXECUTE QUERY
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		if (!is_null($arg->unitID)) { $statement->bindParam(':unitID', $arg->unitID, PDO::PARAM_INT); }
		if (!is_null($arg->buildingID)) { $statement->bindParam(':buildingID', $arg->buildingID, PDO::PARAM_INT); }
		if (!is_null($arg->siteID)) { $statement->bindParam(':siteID', $arg->siteID, PDO::PARAM_INT); }
		if (!is_null($arg->creator)) { $statement->bindParam(':creator', $arg->creator, PDO::PARAM_INT); }
		if (!is_null($arg->unitName)) { $statement->bindParam(':unitName', $arg->unitName, PDO::PARAM_STR); }
		if (!is_null($arg->unitURL)) { $statement->bindParam(':unitURL', $arg->unitURL, PDO::PARAM_STR); }
		if (!is_null($arg->unitStatus)) { $statement->bindParam(':unitStatus', $arg->unitStatus, PDO::PARAM_STR); }
		$statement->execute();

		// WRITE QUERY RESULTS TO ARRAY
		while ($row = $statement->fetch()) { $this->units[] = $row; }

	}

	public function units() {

		return $this->units;

	}

	public function unitCount() {

		return count($this->units);

	}

}

final class UnitListParameters {

	// list filters
	public $unitID;
	public $buildingID;
	public $siteID;
	public $creator;
	public $unitName;
	public $unitURL;
	public $unitStatus;
	public $unitEnabled;

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
		$this->unitID = null;
		$this->buildingID = null;
		$this->siteID = $_SESSION['siteID'];
		$this->creator = null;
		$this->unitName = null;
		$this->unitURL = null;
		$this->unitStatus = null;
		$this->unitEnabled = null; // [null => either; true => enabled only; false => not enabled only]

		// view parameters
		$this->currentPage = null;
		$this->numberOfPages = null;

		// results, order, limit, offset

		$this->resultSet = array();
		$unit = new Unit();
		foreach ($unit AS $key => $value) {
			$this->resultSet[] = array('field' => 'building_Unit.'.$key, 'alias' => $key);
		}

		$this->orderBy = array(
			array('field' => 'building_Unit.created', 'sort' => 'DESC')
		);

		$this->limit = null;
		$this->offset = null;

	}

}

final class UnitUtilities {

	public static function unitUrlExists($unitURL) {

		$arg = new UnitListParameters();
		$arg->unitURL = $unitURL;
		$unitList = new UnitList($arg);

		if ($unitList->unitCount() > 0) {
			return true;
		} else {
			return false;
		}

	}

	public static function getUnitWithURL($unitURL) {

		$unitID = null;

		$arg = new UnitListParameters();
		$arg->unitURL = $unitURL;
		$unitList = new UnitList($arg);
		$units = $unitList->units();

		if (count($units)) {
			$unitID = $units[0]['unitID'];
		}

		return $unitID;

	}

}

?>