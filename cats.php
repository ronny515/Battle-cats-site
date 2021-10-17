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
	
	if(isset($_GET['type'])) {
		$type=$_GET['type'];
		$query = "SELECT `id`,`cat_id`,`form`,`name` FROM `catunit_j` WHERE `type` = ? ORDER BY `cat_id` ASC,`form` ASC";

		}
	elseif (isset($_GET['enemy'])){
		$type=(int)($_GET['enemy']);
		$enemy=(int)($_GET['enemy']);
		$query = "SELECT `catunit_j`.`id`,`skills`.`cat_id`,`skills`.`form`,`catunit_j`.`name` FROM `catunit_j`,`skills` WHERE `skills`.`enemy_type` & ? = ? AND `skills`.`cat_id`=`catunit_j`.`cat_id` AND `skills`.`form`=`catunit_j`.`form`";
		}
	elseif (isset($_GET['effect'])){
		$type=(int)($_GET['effect']);
		//$effect=(int)($_GET['effect']);
		$query = "SELECT `catunit_j`.`id`,`skills`.`cat_id`,`skills`.`form`,`catunit_j`.`name` FROM `catunit_j`,`skills` WHERE `effect` = ? AND `skills`.`cat_id`=`catunit_j`.`cat_id` AND `skills`.`form`=`catunit_j`.`form`";
		}
	elseif (isset($_GET['event'])){
		$type=(int)($_GET['event']);
		$query = "SELECT `catunit_j`.`id`,`catunit_j`.`cat_id`,`catunit_j`.`form`,`catunit_j`.`name` FROM `catunit_j`,`event` WHERE `event` = ? AND `catunit_j`.`cat_id`=`event`.`cat_id` ";
		}
		
	else echo "<p>no data</p>";
	
	$type=(int)$type;
	$stmt = $db->prepare($query);
	if(isset($enemy))
	$stmt->bind_param('ss',$type,$type);
	else $stmt->bind_param('s',$type);
	$stmt->execute();
	$stmt->store_result();
	$num=$stmt->num_rows;
	$stmt->bind_result($id, $cat_id, $form,$name);
	
	

	if ($num==0) {
       echo "<p>找不到資料</p>";
       exit;
    }
	echo $num."隻貓咪符合<br>";
	
	$query2 = "SELECT * FROM `name_trans` WHERE `name_jp` = ? ";
	for ($i=1;$i<=$num;$i++){

		if(isset($cat_id))$temp=$cat_id;
		$stmt->fetch();
		if(isset($temp))
		if($temp!=$cat_id) echo "<br>\n";
		
		
		$stmt2 = $db->prepare($query2);
		$stmt2->bind_param('s',$name);
		$stmt2->execute();
		$stmt2->store_result();
		if (!$stmt2) {
		   echo "<p>no data</p>".$stmt2;
		   exit;
		}
		$stmt2->bind_result($name_tw, $name_jp, $name_en);
		$stmt2->fetch();
		if (!$name_tw) {
		   $name_tw="尚無翻譯";
		   }
		
		
		echo "".$cat_id."-".$form;
		echo "  <a href=\"cats1.php?catid=".$id."\">".$name_tw." (".$name.")";
		echo "</a><img src=\"".get_img($cat_id,$form)."\" alt=\"\"/>\t";
		//echo "</a><img src=\"image/".$cat_id."-".$form.".png\" alt=\"\"/>\t";
		
	}
	
	
	$stmt2->free_result();
    $stmt->free_result();
	$db->close();
	




include('./template/footer.html');
?>