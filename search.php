<?php

session_start();
// Include the db.php file where the user name data is stored for webadmin
require_once('db.php');
$conn;
echo "Wine Search Page";
//connection to the database using pdo
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

?>
<!-- Form for the search data-->
<div id = 'form'>
	<form method ="GET" action ="answer.php">
		<fieldset>
			<!-- Get the wine and winery inputs using text fields -->
			<label for = "winename">Wine Name:</label>
			<input type="text" name="winename" id="winename" /><br />
			<label for = "wineryname">Winery Name:</label>
			<input type="text" name="wineryname" id="wineryname" /><br />
			<!-- Drop down list for the region-->
			<label for = "region">Region: </label>
			<select name = "region">
			<?php
			// For each loop that will query the database for regions and loop through returned results
				foreach($conn->query("SELECT * FROM region") as $row)
				{
					// Foreach region get the region name then add it to the dropdown list
					$name = $row["region_name"];
        				echo "<option value= '$name'>$name</option>";
				}
			?>
                        </select>
			<br>
			<!-- Drop down list for variety of grape-->
			<label for = "grape">Grape Variety: </label>
			<select name = "grape">
			<!-- Added option for all so user doesn't have to pick specific option-->
			<option value = 'All'>All </option>
			<?php
				// Again using a foreach loop with query to select all grape_variety
				foreach($conn->query("SELECT * FROM grape_variety") as $row)
				{
					// Now getting the grape_variety and adding to drop down list
					$name = $row["variety"];
					echo "<option value = '$name'>$name</option>";
				}
			?>
			</select>
			<br />
			<?php
				// Get the min and max year from the wine table in the database
				// Initialised the min and max values max as 0 and min as 9999 as year will not go past 9999
				$maxyear = 0;
				$minyear = 9999;
				// Loop through wine table
				foreach($conn->query("SELECT * FROM wine") as $row)
				{
					// Compare the year to current max and replace it if greater
					if($row["year"] > $maxyear)
                                        {
                                                $maxyear = $row['year'];
                                        }
					// Same for min but to change it if lower
                                        if($row["year"] < $minyear)
                                        {
                                                $minyear = $row['year'];
                                        }

				}
				// Then create the inputs using with max and min being the maxyear and minyear from above loop
				echo "<label for = 'range'>Range of Years: </label>";
				echo "<input type='number' name = 'minyear' id='minyear' min='$minyear' max='$maxyear'  />";
				echo "<label for = 'maxyear'> to </label>";
				echo "<input type = 'number' name = 'maxyear' id = 'minyear' min= '$minyear' max='$maxyear'/>";
			?>
			<br />
			<!-- Don't need to do any database checking for next inputs -->
			<label for = "minstock">Minimum in stock: </label>
			<input type="number" name ="minstock" id="minstock" min = "0"  /><br />
			<label for = "minordered">Minimum Wines Ordered: </label>
                        <input type="number" name ="minordered" id="minordered" min = "0" /><br />
			<label for = "minprice"> Price Range: </label>
			<?php
				// Now for getting the minimum price have initialised the minimum as extremely high number
				// so can check for anything lower than that
				$minprice = 10000000;
				// Loop through the items table
				foreach($conn->query("SELECT * FROM items") as $row)
				{
					// Same as for the minyear compare and replace if lower
					if($row["price"] < $minprice)
					{
						$minprice = $row["price"]; 
					}
				}
				// Add the inputs using the minprice as the min for each
				echo "<input type = 'number' name = 'minprice' id = 'minprice' min = '$minprice'/>";
				echo "<label for = 'maxprice'> to </label>";
				echo "<input type = 'number' name = 'maxprice' id = 'maxprice' min = '$minprice' />";
				// Making connection to database null as no longer needed
				$conn = null;
			?>
			<br />
			<input type="submit" value="Submit" />
		</fieldset>
	</form>
</div>
<?php

	if(!empty($_SESSION["error"]))
{
        echo $_SESSION["error"];
        $_SESSION["error"] = "";

}

?>
