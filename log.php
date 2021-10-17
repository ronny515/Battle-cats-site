<?
include('./func/function.php');
include('./template/header.html');
header("Content-Type:text/html; charset=utf-8");
include('./menu.php');
    @$db = new mysqli($dbhost, $dbuser, $dbpw , $dbname);
	@$db -> set_charset("utf8");
    if (mysqli_connect_errno()) {
       echo "<p>eeError: Could not connect to database.<br/>
             Please try again later.</p>";
       exit;
    }
    $query = "SELECT * FROM u_log ORDER BY `id` DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id,$type,$message,$datetime);
	echo "<p>Number of items found: ".$stmt->num_rows."</p>";
	while($stmt->fetch()) {
		
		echo "<div class=\"row\" ><div class=\"col-2\">No.".$id."</div><div class=\"col\" style=\"background-color:lavenderblush;\">".$message."</div><div class=\"col-3\">".$datetime."</div></div>\n";
	}

include('./template/footer.html');
?>