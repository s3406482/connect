<?php
	session_start();
	// Getting all variables needed from GET form from search.php
	$winename = $_GET["winename"];
	$wineryname = $_GET["wineryname"];
	$region = $_GET["region"];
	$grape = $_GET["grape"];
	$maxyear = $_GET["maxyear"];
	$minyear = $_GET["minyear"];
	$minstock = $_GET["minstock"];
	$minordered = $_GET["minordered"];
	$minprice = $_GET["minprice"];
	$maxprice = $_GET["maxprice"];
	
	if($maxyear < $minyear)
	{
		$_SESSION["error"] = "ERROR MAX DATE RANGE MUST BE GREATER THAN MIN DATE RANGE!!!";
		header("Location: search.php");
		exit;
	}
	if($maxprice  < $minprice)
	{
		$_SESSION["error"] = "ERROR MAX PRICE RANGE MUST BE GREATER THAN MIN PRICE RANGE!!!";
                header("Location: search.php");
                exit;
	}
	
	
	// Session string of results this is so can pass this string to results.php 
	$_SESSION["result"] = "";

	require_once('db.php');
	$conn;
	try {
        	$conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PW);
       	 	// set the PDO error mode to exception
        	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	}
	// Catch exception if connection fails
	catch(PDOException $e)
    	{
        	echo "Connection failed: " . $e->getMessage();
    	}
	// Initial query this will return all needed columns will SELECT all needed columns or categorys, join all the tables together so can access them all
	// in the one query
	$query = "SELECT wine.wine_name, wine.year, winery.winery_name, region.region_name, grape_variety.variety, inventory.on_hand, inventory.cost, SUM( items.qty ) 
		FROM winery
		JOIN region ON winery.region_id = region.region_id
		JOIN wine ON winery.winery_id = wine.winery_id
		JOIN wine_variety ON wine.wine_id = wine_variety.wine_id
		JOIN grape_variety ON wine_variety.variety_id = grape_variety.variety_id
		JOIN inventory ON wine.wine_id = inventory.wine_id
		JOIN items ON wine.wine_id = items.wine_id ";

	// Counter to ensure WHERE statement only added to first condition
	$count = 0;
	// Use if statements to check if field empty and if not add the SQL statement and then add the condition to the query
	if($winename != "")
	{
		$query = addSQLStatement($query, $count);
		// Use the LIKE and wildcard % for partial input searches
		$query = $query. "wine_name LIKE \"%$winename%\" ";
		$count++;
	}
	if($wineryname != "")
	{
		$query = addSQLStatement($query, $count);
                // Use the LIKE and wildcard % for partial input searches
                $query = $query. "winery_name LIKE \"%$wineryname%\" ";
                $count++;

	}
	if($region != "All")
	{
		$query = addSQLStatement($query, $count);
		$query = $query. "region_name = \"$region\" ";
		$count++;

	}
	if($grape != "All")
        {
                $query = addSQLStatement($query, $count);
                $query = $query. "variety = \"$grape\" ";
                $count++;

        }
	// Checking max and min year variables then adding condition to query
	if(!empty($maxyear))
        {
                $query = addSQLStatement($query, $count);
                $query = $query. "year <= $maxyear ";
                $count++;
	}
	if(!empty($minyear))
	{
		$query = addSQLStatement($query, $count);
		$query = $query. "year >= $minyear ";
		$count++;
	}
	// Checking minimun stock and adding it to query
	if(!empty($minstock))
	{
		$query = addSQLStatement($query, $count);
                $query = $query. "on_hand >= $minstock ";
                $count++;
	}
	// Checking the max and min price and adding them to query
	if(!empty($minprice))
        {
                $query = addSQLStatement($query, $count);
                $query = $query. "cost >= $minprice ";
                $count++;
        }
	if(!empty($maxprice))
        {
                $query = addSQLStatement($query, $count);
                $query = $query. "cost <= $maxprice ";
                $count++;
        }
	// This is group by condition so all the wine is grouped together by the id allowing sum of ordered to be easily calculated
	$query = $query."GROUP BY items.wine_id";
	// This has to be added to query last because as to add a condition to SUM HAVING is needed
	if(!empty($minordered))
	{
                $query = $query. " HAVING sum(items.qty) >= $minordered ";
                $count++;
	}
	// Append the table to string for results.php
	$_SESSION["result"] = $_SESSION["result"]. "<table border = '1'>";
	// Append each row to the string for results.php
	foreach($conn->query($query) as $row)
        {
		$_SESSION["result"] = $_SESSION["result"]. "<tr>";
		$_SESSION["result"] = $_SESSION["result"]. "<td>$row[0]</td> ";
		$_SESSION["result"] = $_SESSION["result"]. "<td>$row[1]</td> ";
		$_SESSION["result"] = $_SESSION["result"]. "<td>$row[2]</td> ";
		$_SESSION["result"] = $_SESSION["result"]. "<td>$row[3]</td> ";
		$_SESSION["result"] = $_SESSION["result"]."<td>$row[4]</td> ";
		$_SESSION["result"] = $_SESSION["result"]."<td>$row[5]</td> ";
		$_SESSION["result"] = $_SESSION["result"]."<td>$row[6]</td> ";
		$_SESSION["result"] = $_SESSION["result"]."<td>$row[7]</td> ";
		$_SESSION["result"] = $_SESSION["result"]."</tr>";
        }
	$_SESSION["result"] = $_SESSION["result"]."</table>";
	// Redirect to results.php to show results
	header("Location: results.php");
	exit;
	// This function will check if WHERE already added to query by using a counter if it has it will add AND instead then return the query
	function addSQLStatement($query, $count)
	{
		if($count == 0)
		{
			$query = $query."WHERE ";
		}
		else
		{
			$query = $query."AND ";
		}
		return $query;
	}
?>
