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
		$this->residenceURL = '';

		if ($residenceID) {

			$nucleus = Nucleus::getInstance();

			$whereClause = array();

			$whereClause[] = 'siteID = :siteID';
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

?>