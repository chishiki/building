
DROP TABLE IF EXISTS `building_Building`;

CREATE TABLE `building_Building` (
    `buildingID` int(12) NOT NULL AUTO_INCREMENT,
    `siteID` int(12) NOT NULL,
    `creator` int(12) NOT NULL,
    `created` datetime NOT NULL,
    `updated` datetime NULL,
    `deleted` int(1) NOT NULL,
    `buildingName` varchar(255) NOT NULL,
    `buildingDescription` text NOT NULL,
    `buildingStartDate` date NULL,
    `buildingEndDate` date NULL,
    `buildingStatus` varchar(20) NOT NULL,
    `buildingPublished` int(1) NOT NULL,
    PRIMARY KEY (`buildingID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



DROP TABLE IF EXISTS `building_FacilityReservation`;

CREATE TABLE `building_FacilityReservation` (
    `reservationID` int(12) NOT NULL AUTO_INCREMENT,
    `buildingID` int(12) NOT NULL,
    `facilityID` int(12) NOT NULL,
    `residentID` int(12) NULL,
    `siteID` int(12) NOT NULL,
    `creator` int(12) NOT NULL,
    `created` datetime NOT NULL,
    `updated` datetime NULL,
    `deleted` int(1) NOT NULL,
    `reservationNotes` text NOT NULL,
    `reservationStartDate` date NULL,
    `reservationEndDate` date NULL,
    `reservationStatus` varchar(20) NOT NULL,
    `reservationApproved` int(1) NOT NULL,
    PRIMARY KEY (`reservationID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



DROP TABLE IF EXISTS `building_ReservableFacility`;

CREATE TABLE `building_ReservableFacility` (
    `facilityID` int(12) NOT NULL AUTO_INCREMENT,
    `buildingID` int(12) NOT NULL,
    `siteID` int(12) NOT NULL,
    `creator` int(12) NOT NULL,
    `created` datetime NOT NULL,
    `updated` datetime NULL,
    `deleted` int(1) NOT NULL,
    `facilityName` varchar(20) NOT NULL,
    `facilityDescription` text NOT NULL,
    `facilityStartDate` date NULL,
    `facilityEndDate` date NULL,
    `facilityStatus` varchar(20) NOT NULL,
    `facilityEnabled` int(1) NOT NULL,
    PRIMARY KEY (`facilityID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



DROP TABLE IF EXISTS `building_Resident`;

CREATE TABLE `building_Resident` (
    `residentID` int(12) NOT NULL AUTO_INCREMENT,
    `unitID` int(12) NULL,
    `siteID` int(12) NOT NULL,
    `creator` int(12) NOT NULL,
    `created` datetime NOT NULL,
    `updated` datetime NULL,
    `deleted` int(1) NOT NULL,
    `residentName` varchar(255) NOT NULL,
    `residentStartDate` date NULL,
    `residentEndDate` date NULL,
    `residentStatus` varchar(20) NOT NULL,
    `residentEnabled` int(1) NOT NULL,
    PRIMARY KEY (`residentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
