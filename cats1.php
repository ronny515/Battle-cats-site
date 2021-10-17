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
	else 	$lv_t=30;
	//echo "lv_t".$lv_t;
	if(isset($_GET['catid'])){
		$temp=$_GET['catid'];
		//if(CheckInt($temp)==0)	header('Location: ./error.php');
		
	}
	else $temp=1;
	//echo gettype($_GET['catid']);
    $query = "SELECT * FROM catunit_j WHERE id = ? ORDER BY `id` ASC";
    $stmt = $db->prepare($query);
	$stmt->bind_param('s',$temp );
    $stmt->execute();
    $stmt->store_result();
	$num=$stmt->num_rows;
	if ($num==0) {
       header('Location: ./error.php');
       exit;
    }
    $stmt->bind_result($id, $cat_id, $form,$name,$type , $pic , $hp, $atk ,$dps,$atktype ,$kb, $speed, $arange ,$price ,$aspeed , $aspeed2, $pspeed , $lv1, $lv2);
//,$useskill , $catdescribe , $howget , $combo , $version
	$stmt->fetch();
	
	$query2 = "SELECT * FROM `name_trans` WHERE `name_jp` = ? ";
    $stmt2 = $db->prepare($query2);
	$stmt2->bind_param('s',$name );
    $stmt2->execute();
    $stmt2->store_result();
	if (!$stmt2) {
       echo "<p>no data stmt2</p>".$stmt2;
       exit;
	   }
	$stmt2->bind_result($name_tw, $name_jp, $name_en);
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
      <td>Lv<?echo $lv_t;?></td>
      <td>Lv<?echo $lv2;?></td>
	  <td><? get_cat_type($type); ?></td>
    </tr>

    <tr>
      <td colspan="6" align="center"><?echo "<img src=\"".get_img($cat_id,$form)."\" alt=\"\"/>";?></td>

    </tr>

    <tr>
      <td>HP</td>
      <td><?echo (get_hp($lv_t,$hp));?></td>
      <td>KB</td>
      <td><?echo $kb;?></td>
      <td>攻速1</td>
      <td><?echo $aspeed;?></td>
    </tr>
    <tr>
      <td>ATK</td>
      <td><?echo (get_hp($lv_t,$atk));?></td>
      <td>Speed</td>
      <td><?echo $speed;?></td>
      <td>攻速2</td>
      <td><?echo $aspeed2;?></td>
    </tr>
    <tr>
      <td>DPS</td>
      <td><?if($aspeed!=0)echo round(get_hp($lv_t,$atk) * 30 / $aspeed);
	  else echo "0";?></td>
      <td>Range</td>
      <td><?echo $arange;?></td>
      <td>生產時間</td>
      <td><?echo $pspeed;?></td>
    </tr>
    <tr>
      <td>攻擊範圍</td>
      <td><? echo $atktype==0? "單體":"範圍";?></td>
      <td>$</td>
      <td><?echo ($price*1.5);?></td>
      <td></td>
      <td></td>
    </tr>
	
	<tr>
      <td colspan="6"><?getcskill($cat_id,$form);?></td>
    </tr>
  </tbody>
</table>

<?

    $stmt->free_result();
	$db->close();
	
	





include('./template/footer.html');
?>