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
	if(isset($_GET['lv']))	$lv_t=$_GET['lv'];
	else 	$lv_t=1;
	//echo "lv_t".$lv_t;
	if(isset($_GET['id']))
    $temp=$_GET['id'];
	else $temp=1;
    $query = "SELECT * FROM enemy WHERE id = ? ORDER BY `id` ASC";
    $stmt = $db->prepare($query);
	$stmt->bind_param('s',$temp );
    $stmt->execute();
    $stmt->store_result();
	$num=$stmt->num_rows;
	if ($num==0) {
       echo "<p>no data</p>";
       exit;
    }
    $stmt->bind_result($id, $name,$type , $pic , $hp, $atk ,$dps,$atktype ,$kb, $speed, $arange ,$price ,$aspeed , $aspeed2);
//,$useskill , $catdescribe , $howget , $combo , $version
	$stmt->fetch();
	
	$query2 = "SELECT * FROM `ename_trans` WHERE `id` = ? ";
    $stmt2 = $db->prepare($query2);
	$stmt2->bind_param('s',$id );
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
	?>

<table class="table-bordered table-hover">
  <tbody>
    <tr>
      <td>No.<?echo $id;?></td>
	  <td colspan="2"><?echo $name_tw."(".$name.")";?></td>
      <td><? ?></td>
      <td><? ?></td>
	  <td><? getetype($type); ?></td>
    </tr>

    <tr>
      <td colspan="6" align="center"><?echo "<img src=\"".get_img_e($id)."\" alt=\"\"/>";?></td>

    </tr>

    <tr>
      <td>HP</td>
      <td><?echo $hp;?></td>
      <td>KB</td>
      <td><?echo $kb;?></td>
      <td>攻速1</td>
      <td><?echo $aspeed;?></td>
    </tr>
    <tr>
      <td>ATK</td>
      <td><?echo $atk;?></td>
      <td>Speed</td>
      <td><?echo $speed;?></td>
      <td>攻速2</td>
      <td><?echo $aspeed2;?></td>
    </tr>
    <tr>
      <td>DPS</td>
      <td><?echo round($atk * 30 / ($aspeed+$aspeed2));?></td>
      <td>Range</td>
      <td><?echo $arange;?></td>
      <td></td>
      <td><? ?></td>
    </tr>
    <tr>
      <td>攻擊範圍</td>
      <td><? echo $atktype==0? "單體":"範圍";?></td>
      <td>$</td>
      <td><?echo $price;?></td>
      <td></td>
      <td></td>
    </tr>
  </tbody>
</table>

<?

    $stmt->free_result();
	$db->close();
	
	//getcskill($cat_id,$form);





include('./template/footer.html');
?>