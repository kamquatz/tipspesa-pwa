CREATE TABLE `premium` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(128) NULL,
  `validity` int DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `mpesa` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `phone` varchar(20) NOT NULL,
  `MerchantRequestID` varchar(128) NULL,
  `CheckoutRequestID` varchar(128) NULL,
  `MpesaReceiptNumber` varchar(20) NULL,
  `Amount` double DEFAULT NULL,
  `TransactionDate` varchar(32) NULL,
  `created_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

select m1.* from matches m1 
join matches m2 on m2.home=m1.home and m2.away=m1.away and m2.match_day=m1.match_day and m2.prediction='X2'
where m1.prediction='OV1.5'
union
select m3.* from matches m3
join matches m4 on m4.home=m3.home and m4.away=m3.away and m4.match_day=m3.match_day and m4.prediction='OVER 1.5'
where m3.prediction='1X'

CREATE TABLE `autobets` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `token` TEXT DEFAULT NULL,
  `max_slips` int DEFAULT NULL,
  `stake` int DEFAULT NULL,
  `last_placed` DATETIME DEFAULT,
  `status` int(11) DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `match`;
CREATE TABLE `match` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `parent_match_id` VARCHAR(32) NOT NULL,
  `home` VARCHAR(64) NOT NULL,
  `away` VARCHAR(64) NOT NULL,
  `kickoff`  DATETIME NULL DEFAULT NULL,
  `status` INT DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
UNIQUE KEY `parent_match_id` (`parent_match_id`)
);

DROP TABLE IF EXISTS `market`;
CREATE TABLE `market` (
  `id` INT(11) AUTO_INCREMENT NOT NULL,
  `market_id` VARCHAR(32) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `status` INT DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
UNIQUE KEY `market_id` (`market_id`)
);

DROP TABLE IF EXISTS `odd`;
CREATE TABLE `odd` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `parent_match_id` VARCHAR(32) NOT NULL,
  `market_id` VARCHAR(32) NOT NULL,
  `display` VARCHAR(64) NOT NULL,
  `odd_key` DOUBLE DEFAULT NULL,
  `odd_value` DOUBLE DEFAULT NULL,
  `outcome_id` VARCHAR(4) NOT NULL,
  `status` INT DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `prediction`;
CREATE TABLE `prediction` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `parent_match_id` VARCHAR(32) NOT NULL,
  `outcome_id` VARCHAR(4) NOT NULL,
  `result` VARCHAR(64) DEFAULT NULL,
  `outcome` BOOLEAN DEFAULT NULL,
  `status` INT DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
UNIQUE KEY `parent_match_id_outcome_id` (`parent_match_id`,`outcome_id`)
);

DROP TABLE IF EXISTS `placed_prediction`;
CREATE TABLE `placed_prediction` (
  `id` int(11) AUTO_INCREMENT NOT NULL,
  `prediction_id` VARCHAR(32) NOT NULL,
  `customer_id` VARCHAR(32) NOT NULL,
  `odd_value` DOUBLE DEFAULT NULL,
  `outcome` BOOLEAN DEFAULT NULL,
  `status` INT DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
UNIQUE KEY `prediction_id_customer_id` (`prediction_id`,`customer_id`)
);

INSERT INTO `market` (`market_id`, `name`, `status`) 
VALUES 
('1', '1X2', '1'),
('29', 'BOTH TEAMS TO SCORE (GG/NG)', '1'),
('10', 'DOUBLE CHANCE', '1'),
('1', '1X2', '1'),
('1', '1X2', '1'),
('1', '1X2', '1'),
('1', '1X2', '1'),
('1', '1X2', '1'),
('1', '1X2', '1'),
('1', '1X2', '1'),
('1', '1X2', '1'),
('1', '1X2', '1'),
('1', '1X2', '1'),
('1', '1X2', '1'),
('1', '1X2', '1')



;



Home
Draw
Away
Gg
Ng
Un15
Ov15
Un15
Ov25
Un35
Ov35

CornersOver7.5
CornersOver8.5
CornersUnder11.5
CornersUn12.5



Running Threads
OddChecker thread update odds every 5mins

Predictor thread picks constant reducing odds just 10 minutes b4 game starts
BetPlacer thread places groupedBetslips 

Outcome thread updates every 1 minute (liveGames results)

Dashboard
Plot a line chart of (y) against time(x):
          text=match 
          color=outcome (win=green, loss=red,pending=blue)


