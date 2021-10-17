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
	
?>
<form action="search.php" method="post">
  <p><strong>搜尋選擇</strong>
  <select name="type">
  <option value="cat">貓咪角色</option>
  <option value="enemy">敵人</option>
  </select>
  <fieldset>
    <strong>搜尋名稱</strong>
    <input name="searchterm" type="text" size="40">

  
<input type="submit" value="search" /></p>
    </fieldset>
	</form>
<?
	
	
	
	//echo "1".$_POST['type']."1";
	if(isset($_POST['type'])) {
		$type=$_POST['type'];
		
		if($type=="cat"){
		$query = "SELECT `id`,`cat_id`,`form`,`name`,`name_tw` FROM `catunit_j`,`name_trans` WHERE `catunit_j`.`name` = `name_trans`.`name_jp` AND `name_trans`.`name_tw` LIKE ? ORDER BY `cat_id` ASC,`form` ASC";
		echo "貓咪資料 ";
		}
		if($type=="enemy"){
		$query = "SELECT `enemy`.`id`,`name`,`name_tw` FROM `enemy`,`ename_trans` WHERE `enemy`.`name` = `ename_trans`.`name_jp` AND `ename_trans`.`name_tw` LIKE ? ORDER BY `enemy`.`id` ASC";
		echo "敵人資料 ";
		}
			//header('Location: ./error.php');
		}
	elseif (isset($_POST['enemy'])){
		/*$type=(int)($_POST['enemy']);
		$enemy=(int)($_POST['enemy']);
		$query = "SELECT `catunit_j`.`id`,`skills`.`cat_id`,`skills`.`form`,`catunit_j`.`name` FROM `catunit_j`,`skills` WHERE `skills`.`enemy_type` & ? = ? AND `skills`.`cat_id`=`catunit_j`.`cat_id` AND `skills`.`form`=`catunit_j`.`form`";*/
		}
	
	if( !isset($_POST['type']) || !isset($_POST['searchterm']) ){
		echo "<p>選擇搜尋貓咪或敵人<br></p>";
	}
	elseif( isset($_POST['type']) && isset($_POST['searchterm']) ){
	$searchterm="%{$_POST['searchterm']}%";
	$stmt = $db->prepare($query);	
	$stmt->bind_param('s',$searchterm);
	$stmt->execute();
	$stmt->store_result();
	$num=$stmt->num_rows;
	if($type=="cat")
	$stmt->bind_result($id, $cat_id, $form,$name,$name_tw);
	if($type=="enemy")
	$stmt->bind_result($id,$name,$name_tw);

	if ($num==0) {
       echo "<p>找不到資料</p>";
       exit;
    }
	echo $num."個符合<br>";
	
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
		
		if($type=="cat"){
		echo "".$cat_id."-".$form;
		echo "  <a href=\"cats1.php?catid=".$id."\">".$name_tw." (".$name.")";
		echo "</a><img src=\"".get_img($cat_id,$form)."\" alt=\"\"/>\t";
		}
		if($type=="enemy"){
		echo "".$id."";
		echo "\t<a href=\"enemy1.php?id=".$id."\">".$name_tw."(".$name.")";
		echo "</a><img src=\"".get_img_e($id)."\" alt=\"\"/>\t";
		echo "<br>";
		}
		
	}
	
	
	$stmt2->free_result();
    $stmt->free_result();
	$db->close();
	}




include('./template/footer.html');
?>