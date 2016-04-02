# VendinGoGo
One step closer to becoming an omnipresent being

# Installation
This project uses PHP 7.0.5 and a MySQL database for storing memory.

### Tables
#### Vending Location
```SQL
CREATE TABLE `vendinglocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lat` decimal(10,0) NOT NULL,
  `lng` decimal(10,0) NOT NULL,
  `submittedBy` int(11) NOT NULL,
  `numOfMachines` int(11) DEFAULT '1',
  `createdOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `howToFind` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='Then is meant to represent a Vending Machine Location';
```
