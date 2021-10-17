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
	
	echo "<div class=\"indexcard\">";
	echo "<h5>隨機貓貓</h5>";
for ($i=1;$i<=5;$i++){
    $query = "SELECT `id`,`cat_id`,`form`,`name` FROM catunit_j ORDER BY `cat_id` ASC,`form` ASC";
    $stmt =$db->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id,$cat_id,$form,$name);
	$num=$stmt->num_rows;
	$temp=(rand(1,$num));
	
    $query = "SELECT `id`,`cat_id`,`form`,`name` FROM catunit_j where `id` = ? ORDER BY `cat_id` ASC,`form` ASC";
    $stmt = $db->prepare($query);
	$stmt->bind_param('s',$temp );
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id,$cat_id,$form,$name);
	$stmt->fetch();	
	
		$query2 = "SELECT * FROM `name_trans` WHERE `name_jp` = ? ";
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
		echo "\t<a href=\"cats1.php?catid=".$id."\">".$name_tw." (".$name.")";
		echo "</a><img src=\"".get_img($cat_id,$form)."\" alt=\"\"/>\t\n";
		echo "<br>";
}
	echo "</div>";
	echo "<br>";
	
	echo "<div class=\"indexcard\">";
	echo "<h5>隨機敵人</h5>";
for ($i=1;$i<=5;$i++){
    $query = "SELECT `id`,`name` FROM enemy ORDER BY `id` ASC";
    $stmt =$db->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id,$name);
	$num=$stmt->num_rows;
	$temp=(rand(1,$num));
	
    $query = "SELECT `id`,`name` FROM enemy where `id` = ?  ORDER BY `id` ASC";
    $stmt = $db->prepare($query);
	$stmt->bind_param('s',$temp );
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id,$name);
	$stmt->fetch();	
	
		$query2 = "SELECT * FROM `ename_trans` WHERE `name_jp` = ? ";
		$stmt2 = $db->prepare($query2);
		$stmt2->bind_param('s',$name);
		$stmt2->execute();
		$stmt2->store_result();
		if (!$stmt2) {
		   echo "<p>no data</p>".$stmt2;
		   exit;
		}
		$stmt2->bind_result($eid,$name_tw, $name_jp, $name_en);
		$stmt2->fetch();
		if (!$name_tw) {
		   $name_tw="尚無翻譯";
		   }

		echo "".$id."";
		echo "\t<a href=\"enemy1.php?id=".$id."\">".$name_tw."(".$name.")";
		echo "</a><img src=\"".get_img_e($id)."\" alt=\"\"/>\t\n";
		echo "<br>";
}
echo "</div>";
echo "<br>";


include('./template/thread.html');
include('./template/footer.html');
?>