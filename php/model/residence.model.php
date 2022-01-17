<?php

/*

CREATE TABLE `building_Residence` (
  `residenceID` int NOT NULL AUTO_INCREMENT,
  `siteID` int NOT NULL,
  `creator` int NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `deleted` int NOT NULL,
  `residenceEnglish` varchar(50) NOT NULL,
  `residenceJapanese` varchar(50) NOT NULL,
  `residenceDescriptionEnglish` text NOT NULL,
  `residenceDescriptionJapanese` text NOT NULL,
  `residenceClassification` int NOT NULL,
  `residenceEnabled` int NOT NULL,
  `residenceStatus` varchar(20) NOT NULL,
  `residenceURL` varchar(100) NOT NULL,
  PRIMARY KEY (`newsID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

*/

final class Residence extends ORM {

	public $residenceID;
	public $siteID;
	public $creator;
	public $created;
	public $updated;
	public $deleted;
	public $residenceEnglish;
	public $residenceJapanese;
	public $residenceDescriptionEnglish;
	public $residenceDescriptionJapanese;
	public $residenceClassification;
	public $residenceEnabled;
	public $residenceStatus;
	public $residenceURL;

	public function __construct($residenceID = null) {

		$dt = new DateTime();

		$this->residenceID = 0;
		$this->siteID = $_SESSION['siteID'];
		$this->creator = $_SESSION['userID'];
		$this->created = $dt->format('Y-m-d H:i:s');
		$this->updated = null;
		$this->deleted = 0;
		$this->residenceEnglish = '';
		$this->residenceJapanese = '';
		$this->residenceDescriptionEnglish = '';
		$this->residenceDescriptionJapanese = '';
		$this->residenceClassification = 0;
		$this->residenceEnabled = 0;
		$this->residenceStatus = 0;
		$this->residenceURL = '';

		if ($residenceID) {

			$nucleus = Nucleus::getInstance();

			$whereClause = array();

			// $whereClause[] = 'siteID = :siteID'; // there may be an admin view that can access residences on other sites
			$whereClause[] = 'deleted = 0';
			$whereClause[] = 'residenceID = :residenceID';

			$query = 'SELECT * FROM building_Residence WHERE ' . implode(' AND ', $whereClause) . ' LIMIT 1';

			$statement = $nucleus->database->prepare($query);
			$statement->bindParam(':siteID', $_SESSION['siteID'], PDO::PARAM_INT);
			$statement->bindParam(':residenceID', $residenceID, PDO::PARAM_INT);
			$statement->execute();

			if ($row = $statement->fetch()) {
				foreach ($row AS $key => $value) { if (property_exists($this, $key)) { $this->$key = $value; } }
			}

		}

	}

	public function residenceName() {
		$newsTitle = $this->residenceEnglish;
		if ($_SESSION['lang'] == 'ja' && !empty($this->residenceJapanese)) {
			$newsTitle = $this->residenceEnglish;
		}
		return $newsTitle;
	}

	public function residenceDescription() {
		$newsContent = $this->residenceDescriptionEnglish;
		if ($_SESSION['lang'] == 'ja' && !empty($this->residenceDescriptionJapanese)) {
			$newsContent = $this->residenceDescriptionEnglish;
		}
		return $newsContent;
	}

	public function markAsDeleted() {

		$dt = new DateTime();
		$this->updated = $dt->format('Y-m-d H:i:s');
		$this->deleted = 1;
		$conditions = array('newsID' => $this->newsID);
		self::update($this, $conditions, true, false, 'building_');

	}

}

final class BuildingResidenceList {

	private $residences;

	public function __construct(BuildingResidenceListParameters $arg) {

		$this->residences = array();

		// WHERE
		$wheres = array();
		$wheres[] = 'building_Residence.deleted = 0';
		if (!is_null($arg->residenceID)) { $wheres[] = 'building_Residence.residenceID = :residenceID'; }
		if (!is_null($arg->siteID)) { $wheres[] = 'building_Residence.siteID = :siteID'; }
		if (!is_null($arg->creator)) { $wheres[] = 'building_Residence.creator = :creator'; }
		if (!is_null($arg->residenceName)) {
			$wheres[] = '(building_Residence.residenceNameEnglish = :residenceName OR building_Residence.residenceNameJapanese = :residenceName)';
		}
		if (!is_null($arg->residenceDescription)) {
			$wheres[] = '(building_Residence.residenceDescriptionEnglish = :residenceDescription OR building_Residence.residenceDescriptionJapanese = :residenceDescription)';
		}
		if (!is_null($arg->residenceClassification)) { $wheres[] = 'building_Residence.residenceClassification = :residenceClassification'; }
		if (!is_null($arg->residenceEnabled === true)) { $wheres[] = 'building_Residence.residenceEnabled = 1'; }
		if (!is_null($arg->residenceEnabled === false)) { $wheres[] = 'building_Residence.residenceEnabled = 0'; }
		if (!is_null($arg->residenceStatus)) { $wheres[] = 'building_Residence.buildingStatus = :buildingStatus'; }
		if (!is_null($arg->residenceURL)) { $wheres[] = 'building_Residence.residenceURL = :residenceURL'; }
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
		$query = 'SELECT ' . $selector . ' FROM building_Residence' . $where . $orderBy;
		if ($arg->limit) { $query .= ' LIMIT ' . ($arg->offset?$arg->offset.', ':'') . $arg->limit; }

		// PREPARE QUERY, BIND PARAMS, EXECUTE QUERY
		$nucleus = Nucleus::getInstance();
		$statement = $nucleus->database->prepare($query);
		if (!is_null($arg->residenceID)) { $statement->bindParam(':residenceID', $arg->residenceID, PDO::PARAM_INT); }
		if (!is_null($arg->siteID)) { $statement->bindParam(':siteID', $arg->siteID, PDO::PARAM_INT); }
		if (!is_null($arg->creator)) { $statement->bindParam(':creator', $arg->creator, PDO::PARAM_INT); }
		if (!is_null($arg->residenceName)) { $statement->bindParam(':residenceName', $arg->residenceName, PDO::PARAM_STR); }
		if (!is_null($arg->residenceDescription)) { $statement->bindParam(':residenceDescription', $arg->residenceDescription, PDO::PARAM_STR); }
		if (!is_null($arg->residenceClassification)) { $statement->bindParam(':residenceClassification', $arg->residenceClassification, PDO::PARAM_INT); }
		if (!is_null($arg->residenceStatus)) { $statement->bindParam(':residenceStatus', $arg->residenceStatus, PDO::PARAM_STR); }
		if (!is_null($arg->residenceURL)) { $statement->bindParam(':residenceURL', $arg->residenceURL, PDO::PARAM_STR); }
		$statement->execute();

		// WRITE QUERY RESULTS TO ARRAY
		while ($row = $statement->fetch()) { $this->residences[] = $row; }

	}

	public function residences() {

		return $this->residences;

	}

	public function residenceCount() {

		return count($this->residences);

	}

}

final class BuildingResidenceListParameters {

	// list filters
	public $residenceID;
	public $siteID;
	public $creator;
	public $residenceName;
	public $residenceDescription;
	public $residenceClassification;
	public $residenceEnabled;
	public $residenceStatus;
	public $residenceURL;

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
		$this->residenceID = null;
		$this->siteID = null;
		$this->creator = null;
		$this->residenceName = null;
		$this->residenceDescription = null;
		$this->residenceClassification = null; // [Studio | 1LDK | 2LDK | <TBD>] // normalize into another table
		$this->residenceEnabled = null; // [null => either; true => featured only; false => not featured only]
		$this->residenceStatus = null; // [occupied | vacant | unavailable | <TBD>]
		$this->residenceURL = null;

		// view parameters
		$this->currentPage = null;
		$this->numberOfPages = null;

		// results, order, limit, offset

		$this->resultSet = array();
		$residence = new Building();
		foreach ($residence AS $key => $value) {
			$this->resultSet[] = array('field' => 'building_Residence.'.$key, 'alias' => $key);
		}

		$this->orderBy = array(
			array('field' => 'building_Residence.created', 'sort' => 'DESC')
		);

		$this->limit = null;
		$this->offset = null;

	}

}

final class BuildingResidenceUtilities {

	public static function residenceUrlExists($residenceURL) {

		$arg = new ResidenceListParameters();
		$arg->residenceURL = $residenceURL;
		$residenceList = new ResidenceList($arg);

		if ($residenceList->residenceCount() > 0) {
			return true;
		} else {
			return false;
		}

	}

	public static function getBuildingWithURL($residenceURL) {

		$residenceID = null;

		$arg = new ResidenceListParameters();
		$arg->residenceURL = $residenceURL;
		$residenceList = new ResidenceList($arg);
		$residences = $residenceList->residences();

		if (count($residences)) {
			$residenceID = $residences[0]['residenceID'];
		}

		return $residenceID;

	}

}

?>