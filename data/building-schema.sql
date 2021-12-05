
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
