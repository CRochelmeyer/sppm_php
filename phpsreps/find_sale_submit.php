<?php
	
	session_start();
	
	// If Search is clicked
	if (isset ($_POST["find_sale"]))
	{
		$errMsg = "";
		$datefrom = htmlspecialchars(trim($_POST["datefrom"]));
		$dateto = htmlspecialchars (trim ($_POST["dateto"]));
		
		require_once ("php/settings.php");		//connection info
		$conn = @mysqli_connect ($host, $user, $pwd, $sql_db);
		
		if (!$conn)
		{
			$errMsg = "Database connection failure!<br \>";
		}else
		{
			$query = "SELECT sale.sale_id, sale.time_created, sale.sale_amount FROM sale WHERE sale.time_created BETWEEN '$datefrom' AND'$dateto';";
						
			$result = mysqli_query ($conn, $query);
			
			if(mysqli_num_rows($result) > 0)
			{
				$success = "<form action=\"delete_sale_submit.php\" method=\"post\" >
				<fieldset>
				<legend>Showing all sales between $datefrom and $dateto</legend>
				<table border=\"1\" style=\"text-align: center;\">
				<tr>
				<th>Select</th>
				<th scope=\"row\">Sale ID</th>
				<th scope=\"row\">Sale Date</th>
				<th scope=\"row\">Total</th>
				<th scope=\"row\">View</th>
				</tr>";
				
				$sidArray = array();
				$i = 0;
				
				while ($row = mysqli_fetch_assoc($result))
				{
					$sid = $row["sale_id"];
					$time = $row["time_created"];
					$amount = $row["sale_amount"];

					$success .= "<tr>
					<td align=\"center\"><input type=\"checkbox\" name=\"checked_id[]\" class=\"checkbox\" value=\"$sid\"/></td>
					<td>$sid</td>
					<td>$time</td>
					<td>$$amount</td>
					<td><input type=\"submit\" name=\"$sid\" value=\"View Sale\" /></td>
					</tr>";
					
					$sidArray[$i] = $sid;
					$i++;
				}
				$success .= "</table></fieldset><input type=\"submit\" name=\"bulk_delete_submit\" value=\"Delete\" onclick=\"return deleteConfirm();\" />
				</form>";
				
				$_SESSION["view_sale_sid"] = $sidArray;
			}
			else
			{
				$errMsg = "No results found!";
			}
			
			mysqli_free_result ($result);
			
			mysqli_close ($conn);
			
			if ($errMsg != "") //check for errors
			{
				$_SESSION["find_sale_result"] = "<p>$errMsg</p>";
				header ("location:sales_management.php");
			}else
			{
				$_SESSION["find_sale_result"] = $success;
				header ("location:sales_management.php");
			}
		}
	}
?>