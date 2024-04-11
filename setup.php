<?php

$createDatabaseSQL = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . ";";

$createTableSQL = "CREATE TABLE IF NOT EXISTS `purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(256) NOT NULL,
  `quantity` int(10) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `purchased_at` bigint(20) unsigned NOT NULL,
  `measure` enum('Liquid500','Liquid1000','Pills') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

$dummyDataSQL = "INSERT INTO purchase (product_name,quantity,amount,purchased_at,measure) VALUES
	 ('exius',29,11.20,1712768125,'Pills'),
	 ('quidem',21,56.40,1712163368,'Liquid500'),
	 ('molestiae',5,472.56,1709571368,'Pills'),
	 ('tempora',8,33995.75,1712768125,'Pills'),
	 ('ipsa',34,18.98,1712163368,'Liquid500'),
	 ('harum',13,110.91,1712768125,'Liquid1000'),
	 ('quisquam',1,35957.30,1712768125,'Liquid1000'),
	 ('sittter',2,11.20,1711990568,'Pills'),
	 ('cumque',8,34.69,1712768125,'Liquid500'),
	 ('eaque',77,110.74,1711990568,'Pills'),
	 ('cupiditate',4,297.86,1712768125,'Liquid1000'),
	 ('quis',62,60.60,1712163368,'Liquid1000'),
	 ('citoos',12,330.00,1712768125,'Liquid500'),
	 ('quia',114,999.99,1712768125,'Liquid1000'),
	 ('deserunt',54,245.01,1710348968,'Pills'),
	 ('quidqas',55,868.90,1712768125,'Pills'),
	 ('voluptas',89,104.57,1711990568,'Pills'),
	 ('amet',34,59.85,1710348968,'Liquid1000'),
	 ('repellendus',44,345.22,1712768125,'Pills'),
	 ('commodi',71,12.30,1709571368,'Pills'),
	 ('enim',35,3467.58,1712768125,'Liquid500'),
	 ('nisia',27,0.82,1709571368,'Liquid1000'),
	 ('nonusamed',22,599.30,1712768125,'Liquid500'),
	 ('hicbir',78,55.32,1709571368,'Pills'),
	 ('recusandae',9,7.80,1712768125,'Liquid1000');";

// DB creating
$createdDB = mysqli_query($link, $createDatabaseSQL);
$dbSelected = mysqli_select_db($link, DB_NAME);
if (!$dbSelected) {
    die ('Can\'t use new database : ' . mysqli_error());
}

//table creating
$createdTable = mysqli_query($link, $createTableSQL);
if ($createdTable) {

    // initial fill table only once
    $res = mysqli_query($link, "SELECT count(*) as cnt FROM purchase");
    $recordCount =(int)mysqli_fetch_assoc($res)['cnt'];

    if (!$recordCount) {
        mysqli_query($link, $dummyDataSQL);
    }

}