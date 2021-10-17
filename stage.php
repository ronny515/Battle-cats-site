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
	
	$temp=1;
    $query = "SELECT * FROM `stage` WHERE id = ? ORDER BY `id` ASC";
    $stmt = $db->prepare($query);
	$stmt->bind_param('s',$temp );
    $stmt->execute();
    $stmt->store_result();
	$num=$stmt->num_rows;
	if ($num==0) {
       header('Location: ./error.php');
       exit;
    }
	$stmt->bind_result($id, $sort1, $sort2, $sort3,$name,$name_jp,$name_en, $energy , $exp, $hp ,$size , $num_e);
	$stmt->fetch();
	echo "<h5>狂亂貓關卡</h5>\n";
	?>
	<table class="table-bordered table-hover">

	<tbody>
	<tr>
		<td>名稱</td>
		<td><?echo $name."<br>".$name_jp."<br>".$name_en;?></td>
	</tr>

	<tr>
		<td>統帥力</td>
		<td><?echo $energy;?></td>
	</tr>
	
	<tr>
		<td>經驗值</td>
		<td><?echo $exp;?></td>
	</tr>

	<tr>
		<td>城堡體力</td>
		<td><?echo $hp;?></td>
	</tr>
	
	<tr>
		<td>場地大小</td>
		<td><?echo $size;?></td>
	</tr>
	
	
	<tr>
		<td>最大敵人數</td>
		<td><?echo $num_e;?></td>
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>


</tbody></table>


	<table class="table-bordered table-hover">
	<tbody>
	<tr>
		<td>敵人</td>
		<td>名稱</td>
		<td>強度</td>
		<td>數量</td>
		<td>城堡血量</td>
		<td>出現時間</td>
		<td>再出現</td>
	</tr>
	<?
	$query = "SELECT * FROM `stage_e` WHERE s_id = ? ORDER BY `t2_hp` DESC";
    $stmt = $db->prepare($query);
	$stmt->bind_param('s',$temp );
    $stmt->execute();
    $stmt->store_result();
	$stmt->bind_result($id,$s_id, $enemy_id, $mul, $e_num,$t2_hp,$time,$time2, $time3 , $boss);
	$num=$stmt->num_rows;
	if ($num==0) {
       header('Location: ./error.php');
       exit;
    }
	

	
	echo $num."隻敵人<br>";

	for ($i=1;$i<=$num;$i++){
	$stmt->fetch();
	
	$query2 = "SELECT * FROM `ename_trans` WHERE `id` = ? ";
	$stmt2 = $db->prepare($query2);
	$stmt2->bind_param('s',$enemy_id);
	$stmt2->execute();
	$stmt2->store_result();
	if (!$stmt2) {
	   echo "<p>no data</p>".$stmt2;
	   exit;
	}
	$stmt2->bind_result($id ,$name_tw, $name_jp, $name_en);
	$stmt2->fetch();
	?>


	<tr>
		<td><?echo "<img src=\"".get_img_e($id)."\" alt=\"\"/>";?></td>
		<td><?if($boss==1)echo "Boss:"; echo "<a href=\"enemy1.php?id=".$id."\">".$name_tw."</a>";?></td>
		<td><?echo $mul."%";?></td>
		<td><?echo $e_num==0? "無限":"".$e_num."";;?></td>
		<td><?echo $t2_hp."%";?></td>
		<td><?echo $time;?></td>
		<td><?echo $time2."~".$time3."";?></td>
	</tr>
	

	<?
	
	}
	
	?>
	</tbody></table>

	
	
	<?
    $stmt->free_result();
	$stmt2->free_result();
	$db->close();
	
//include('./template/stages.html');
//echo "努力建構中";

include('./template/thread.html');


include('./template/footer.html');
?>