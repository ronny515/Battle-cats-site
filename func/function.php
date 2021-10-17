<?
include('./config.inc.php');
//運行時間
	if( !isset($time_start) )
		$time_start = microtime(true);
	//echo $time_start."s start";

	$daytime = date('YmdHis');//隨時間改變的數
	
	//判斷在哪一頁
	//global $file;
	//$file=(basename(__FILE__,".php"));
	$file=(basename($_SERVER['PHP_SELF'],".php"));
	
function menu_where($page) {
	global $file;
	//echo $file."23	";
	if( $file == "cats")$file ="sort";
	//if( $file == "cats1")$file ="sort";
	if( $file == $page)
		echo " active";

	 return;
}
//確認輸入整數
function CheckInt($input) {
	if( gettype($input) == "integer")
		return 1;
	else
		return 0;
}

//分頁功能
class Page {
	//每頁數量
	public $row_p_page=10;
	//計算頁數
	public $num_rows;
	public $total_page;
	//以下須設定
	public $page;
	public $query;
	public $WhichPage;
	
	function PageQuery() {
    @$db = new mysqli($dbhost, $dbuser, $dbpw , $dbname);
	@$db -> set_charset("utf8");
		if($this->query=="")echo "page未設定查詢";
		$stmt = $db->prepare($this->query);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($max_cat_id);
		$stmt->fetch();
		$this->num_rows=$max_cat_id;
	}
	
	function PageCount() {
		if($this->query=="")echo "page未設定查詢";
		$this->total_page=(int)($this->num_rows/$this->row_p_page);
		if($this->num_rows%$this->row_p_page) $this->total_page++;
	}
	
	function ShowPageMenu($page) {
		//換頁選單
		echo "<a href=\"".$this->WhichPage.".php?page=1\">&lt&lt</a> ";
		$page1=$page-4;
		$page2=$page+4;
		if($page-4<=0){
			$page1=1;
			$page2=9;
		}	
		if($this->total_page-$page<=4){
			$page1=$this->total_page-8;
			$page2=$this->total_page;
		}
		for ($i=$page1;$i<=$page2;$i++){
			if($i==$page)echo "  <a href=\"".$this->WhichPage.".php?page=".$i."\" class=\"pagemeunt\" >".$i."</a> ";
			else echo "  <a href=\"".$this->WhichPage.".php?page=".$i."\" class=\"pagemeun\" >".$i."</a> ";
		}
		
		echo "  <a href=\"".$this->WhichPage.".php?page=".$this->total_page."\">&gt&gt</a> ";
		echo "  <a href=\"".$this->WhichPage.".php?page=all\">全部all</a> ";
		echo "<br>";
	}

}
	
function exc_time($stime) {
	if(!isset($stime))
		return "no start time";
	else{
		$time_end = microtime(true);
		$time = ($time_end - $stime)/1000;
		//echo $time."s";
		}
	 return $time;
	}
//取得標題
function get_title($page) {
	switch ($page) {
	case "index":
	return "貓咪大戰爭 攻略網";
	break;
	
	case "catsuni":
	return "貓咪角色一覽 - 貓咪大戰爭 攻略網";
	break;
	
	case "cats1":
	@$db = new mysqli($dbhost, $dbuser, $dbpw , $dbname);
	@$db -> set_charset("utf8");
	if(isset($_GET['catid']))
    $temp=$_GET['catid'];
	else $temp=1;
    $query = "SELECT * FROM catunit_j WHERE id = ? ORDER BY `id` ASC";
    $stmt = $db->prepare($query);
	$stmt->bind_param('s',$temp );
    $stmt->execute();
    $stmt->store_result();
	$num=$stmt->num_rows;
	if ($num==0) {
		header('Location: ./error.php');
       //echo "<p>no data</p>";
       exit;
    }
    $stmt->bind_result($id, $cat_id, $form,$name,$type , $pic , $hp, $atk ,$dps,$atktype ,$kb, $speed, $arange ,$price ,$aspeed , $aspeed2, $pspeed , $lv1, $lv2);
	$stmt->fetch();
	$query2 = "SELECT * FROM `name_trans` WHERE `name_jp` = ? ";
    $stmt2 = $db->prepare($query2);
	$stmt2->bind_param('s',$name );
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
	return $name_tw." - 貓咪大戰爭 攻略網";
	break;
	
	case "sort":
	return "貓咪角色分類 - 貓咪大戰爭 攻略網";
	break;

	case "cats":
	return "貓咪角色分類 - 貓咪大戰爭 攻略網";
	break;

	case "enemy":
	return "敵人資料 - 貓咪大戰爭 攻略網";
	break;
	
	case "enemy1":
	@$db = new mysqli($dbhost, $dbuser, $dbpw , $dbname);
	@$db -> set_charset("utf8");
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
       header('Location: ./error.php');
	   //echo "<p>no data</p>";
       exit;
    }
    $stmt->bind_result($id, $name,$type , $pic , $hp, $atk ,$dps,$atktype ,$kb, $speed, $arange ,$price ,$aspeed , $aspeed2);
	$stmt->fetch();
		$query2 = "SELECT * FROM `ename_trans` WHERE `id` = ? ";
    $stmt2 = $db->prepare($query2);
	$stmt2->bind_param('s',$id );
    $stmt2->execute();
    $stmt2->store_result();
	if (!$stmt2) {
		header('Location: ./error.php');
       //echo "<p>no data</p>".$stmt2;
       exit;
	   }
	$stmt2->bind_result($eid,$name_tw, $name_jp, $name_en);
	$stmt2->fetch();
	if (!$name_tw) {
		$name_tw=$name."尚無翻譯";
		}
	return $name_tw." - 貓咪大戰爭 攻略網";
	break;
	
	case "stage":
	return "關卡資料 - 貓咪大戰爭 攻略網";
	break;
	
	case "log":
	return "更新進度 - 貓咪大戰爭 攻略網";
	break;
	
	case "search":
	return "搜尋 - 貓咪大戰爭 攻略網";
	break;
	
	default:
	return "貓咪大戰爭 攻略網";
	break;
	
	}
	
}


//取得圖片位置
function get_img($cat_id,$form) {
	$temp=(int)( ($cat_id)/100);
	$scr="image/".$temp."/".$cat_id."-".$form."";
	$scr2="image/".$temp."/".$cat_id." (".$form.")";
	//echo $scr.".jpg";
	if( file_exists("".$scr.".jpg") ) return $scr.".jpg";
	if( file_exists("".$scr.".png") ) return $scr.".png";
	if( file_exists("".$scr2.".jpg") ) return $scr2.".jpg";
	if( file_exists("".$scr2.".png") ) return $scr2.".png";
	return;
}

function get_img_e($cat_id) {
	$temp=(int)( ($cat_id)/100);
	$scr="image/e".$temp."/".$cat_id."";
	$scr2="image/e".$temp."/".$cat_id."";
	//echo $scr.".jpg";
	if( file_exists("".$scr.".jpg") ) return $scr.".jpg";
	if( file_exists("".$scr.".png") ) return $scr.".png";
	if( file_exists("".$scr2.".jpg") ) return $scr2.".jpg";
	if( file_exists("".$scr2.".png") ) return $scr2.".png";
	return;
}

//hp,atk計算公式
function get_hp($level,$hp_atk) {
	//echo "level".$level;
	//echo "hp_atk".$hp_atk;
	if(!isset($level))
		return "no start time";
	else if($level<=60) {
		$hp_c = $hp_atk*( $level+4 )/5 * 5/2;
		}
	else if($level > 60) {
	$hp_c = $hp_atk*( $level/2 + 30 + 4 )/5 * 5/2;
		}	
	 return $hp_c;
	}
//顯示對抗的敵人屬性
function getetype($enemy_type) {
	if($enemy_type&1)	echo "紅色 ";
	if($enemy_type&2)	echo "漂浮 ";
	if($enemy_type&4)	echo "黑色 ";
	if($enemy_type&8)	echo "鋼鐵 ";
	if($enemy_type&16)	echo "天使 ";
	if($enemy_type&32)	echo "異星 ";
	if($enemy_type&64)	echo "不死 ";
	if($enemy_type&128)	echo "古代 ";
	if($enemy_type&256)	echo "白色 ";
	if($enemy_type&512)	echo "魔女 ";
	if($enemy_type&1024) echo "使徒 ";
	if($enemy_type&2048) echo "無屬性 ";
	echo "敵人 ";
}
//顯示抗性能力
function getnoeff($enemy_type) {
	if($enemy_type&1)	echo "攻擊力下降 ";
	if($enemy_type&2)	echo "動作停止 ";
	if($enemy_type&4)	echo "動作變慢 ";
	if($enemy_type&8)	echo "打飛 ";
	if($enemy_type&16)	echo "波動 ";
	if($enemy_type&32)	echo "烈波攻擊 ";
	if($enemy_type&64)	echo "傳送 ";
	if($enemy_type&128)	echo "詛咒 ";
	if($enemy_type&256)	echo "毒擊傷害 ";
}


function get_cat_type($type) {
	if($type==1)	echo "基本";
	if($type==2)	echo "EX";
	if($type==3)	echo "稀有";
	if($type==4)	echo "激稀有";
	if($type==5)	echo "超激稀有";
	if($type==6)	echo "傳說稀有 ";

}

//顯示技能
function getcskill($i,$j) {
	if (!$i || !$j) {
		echo "<p>ij參數錯誤</p>";
		return;
	}
	
	@$db = new mysqli($dbhost, $dbuser, $dbpw , $dbname);
	@$db -> set_charset("utf8");
	if (mysqli_connect_errno()) {
       echo "<p>eeError: Could not connect to database.<br/>
             Please try again later.</p>";
       exit;
    }


	$query = "SELECT * FROM `skills` WHERE `cat_id` = $i AND `form` = $j ORDER BY `effect` ASC";
    $stmt = $db->prepare($query);
	//$stmt->bind_param('s',$temp );
    $stmt->execute();
    $stmt->store_result();
	if (!$stmt) {
		
       echo "<p>no data func getskill</p>".$stmt;
       exit;
    }
    $stmt->bind_result($cat_id, $form,$effect,$enemy_type , $time , $time2, $rate ,$extra);
	$num=$stmt->num_rows;
	echo $num."個技能<br>";
	
for ($i=1;$i<=$num;$i++)
{
	$stmt->fetch();
	
	$scr="image/effect/e (".$effect.").jpg";
	echo "<img src=\"".$scr."\" alt=\"\" width=\"20px\" height=\"20px\" />";
	//echo "".$catid."\t".$form."\t".$effect."\t".$enemy_type."\t".$time."\t".$time2."\t".$rate."\t".$extra."<br>";
	switch ($effect) {
	  case "1":
		echo $rate;
		echo "%機率";
		echo "使";
		getetype($enemy_type);
		echo "攻擊力下降".$extra."% ";
		echo $time/30;
		echo "~";
		echo $time2/30;
		echo "s ";
		echo "<br>";
		break;
		
	  case "2":
		echo $rate;
		echo "%機率";
		echo "使";
		getetype($enemy_type);
		echo "動作停止";
		echo $time/30;
		echo "~";
		echo $time2/30;
		echo "s ";
		echo "<br>";
		break;
		
	  case "3":
		echo $rate;
		echo "%機率";
		echo "使";
		getetype($enemy_type);
		echo "動作變慢";
		echo $time/30;
		echo "~";
		echo $time2/30;
		echo "s ";
		echo "<br>";
		break;
		
	  case "4":
		echo "只能攻擊";
		getetype($enemy_type);
		echo "<br>";
		break;

	  case "5":
		echo "善於攻擊";
		getetype($enemy_type);
		echo "<br>";
		break;
		
	  case "6":
		echo "對";
		getetype($enemy_type);
		echo "很耐打";
		echo "<br>";
		break;

	  case "7":
		echo "對";
		getetype($enemy_type);
		echo "超級耐打";
		echo "<br>";
		break;

	  case "8":
		echo "對";
		getetype($enemy_type);
		echo "超大傷害";
		echo "<br>";
		break;
		
	  case "9":
		echo "對";
		getetype($enemy_type);
		echo "極度傷害";
		echo "<br>";
		break;
		
	  case "10":
		echo $rate;
		echo "%機率";
		echo "使";
		getetype($enemy_type);
		echo "打飛";
		echo "<br>";
		break;
		
	  case "11":
		echo "對";
		getetype($enemy_type);
		echo "傳送";
		echo "<br>";
		break;
		
	  case "12":
		echo $rate;
		echo "%機率";
		getetype($enemy_type);
		echo "詛咒";
		echo $time/30;
		echo "s";
		echo "<br>";
		break;
		
	  case "13":
		echo "使";
		getetype($enemy_type);
		echo $rate;
		echo "%機率";
		echo "攻擊無效";
		echo $time/30;
		echo "s ";
		echo "<br>";
		break;
		
	  case "14":
		echo "體力";
		echo $extra;
		echo "%以下";
		echo "攻擊力上升";
		echo "<br>";
		break;
		
	  case "15":
		echo "使";
		echo $rate;
		echo "%機率";
		echo "一次存活";
		echo "<br>";
		break;


	  case "16":
		echo "善於攻城";
		getetype($enemy_type);
		echo "<br>";
		break;

	  case "17":
		echo "";
		echo $rate;
		echo "%機率";
		echo "會心一擊(爆擊)";
		echo "<br>";
		break;
		
	  case "18":
		echo "對";
		getetype($enemy_type);
		echo "終結不死";
		echo "<br>";
		break;

	  case "19":
		echo $rate;
		echo "%機率";
		echo "破壞護頓";
		echo "<br>";
		break;

	  case "20":
		echo $rate;
		echo "%機率";
		echo "混沌一擊";
		echo "<br>";
		break;

	  case "21":
		echo "打到敵人得到很多錢(2倍)";
		echo "<br>";
		break;

	  case "22":
		echo "鋼鐵(傷害1)";
		echo "<br>";
		break;
		
	  case "23":
		echo "打到敵人發出小波動";
		echo "<br>";
		break;
		
	  case "24":
		echo $rate;
		echo "%機率";
		echo "Lv".$extra."波動";
		echo "<br>";
		break;

	  case "25":
		echo $rate;
		echo "%機率";
		echo "Lv".$extra."烈波";
		echo $time/30;
		echo "s ";
		echo "<br>";
		break;

	  case "26":
		echo "無效(";
		getnoeff($extra);
		echo ")";
		echo "<br>";
		break;

	  case "35":
		echo "波動滅止";
		echo "<br>";
		break;

	  case "38":
		echo "遠方攻擊";
		echo $time;
		echo "~";
		echo $time2;
		echo "<br>";
		break;

	  case "39":
		echo "全方位攻擊";
		echo $time;
		echo "~";
		echo $time2;
		echo "<br>";
		break;

	  case "201":
		echo "".$extra."段連續攻擊";
		echo "<br>";
		break;

	  case "202":
		echo "魔女殺手";
		echo "<br>";
		break;

	  case "203":
		echo "使徒殺手";
		echo "<br>";
		break;

	  case "301":
		echo "一次攻擊";
		echo "<br>";
		break;

	  default:
		echo "無特殊能力";
		echo "<br>";
		break;
	}

	}

}


class Meta {
	// Properties
	public $SortTitle;
	public $SortType;
	public $WordNum;
	
	function ShowTitle() {
		echo "<h5>".$this->SortTitle."</h5>";
		//$this->title = $this->SortMenu.$SortTitle;
	}
	
	function ShowMenu() {
		for ($i=0;$i<((count($this->WordNum,1)-count($this->WordNum))/2);$i++){
			echo "<a href=\"cats.php?".$this->SortType."=".$this->WordNum[0][$i]."\">".$this->WordNum[1][$i]."</a>\t";
		}
		echo "<br>";
	}
	
	function ShowImage() {
		$url="image/sort/".$this->$SortType.$this->$WordNum[0][$i].".png";
		echo "<img src=\"".$url."\" alt=\"\"/>";
	}
  
}


function MetaImg($page) {
	
	//圖片
	switch ($page) {
	case "index":
	return "image/site/logo.png";
	break;
	
	case "catsuni":
	return "image/site/logo.png";
	break;
	
	case "cats1":
	@$db = new mysqli($dbhost, $dbuser, $dbpw , $dbname);
	@$db -> set_charset("utf8");
	if(isset($_GET['catid']))
    $temp=$_GET['catid'];
	else $temp=1;
    $query = "SELECT * FROM catunit_j WHERE id = ? ORDER BY `id` ASC";
    $stmt = $db->prepare($query);
	$stmt->bind_param('s',$temp );
    $stmt->execute();
    $stmt->store_result();
	$num=$stmt->num_rows;
	if ($num==0) {
		header('Location: ./error.php');
       //echo "<p>no data</p>";
       exit;
    }
    $stmt->bind_result($id, $cat_id, $form,$name,$type , $pic , $hp, $atk ,$dps,$atktype ,$kb, $speed, $arange ,$price ,$aspeed , $aspeed2, $pspeed , $lv1, $lv2);
	$stmt->fetch();
	return get_img($cat_id,$form);
	break;
	
	case "sort":
	return "image/site/logo.png";
	break;

	case "cats":
	return "image/site/logo.png";
	break;

	case "enemy":
	return "image/site/logo.png";
	break;
	
	case "enemy1":
	@$db = new mysqli($dbhost, $dbuser, $dbpw , $dbname);
	@$db -> set_charset("utf8");
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
       header('Location: ./error.php');
	   //echo "<p>no data</p>";
       exit;
    }
    $stmt->bind_result($id, $name,$type , $pic , $hp, $atk ,$dps,$atktype ,$kb, $speed, $arange ,$price ,$aspeed , $aspeed2);
	$stmt->fetch();

	return get_img_e($id);
	break;
	
	case "stage":
	return "image/site/logo.png";
	break;
	
	case "log":
	return "image/site/logo.png";
	break;
	
	case "search":
	return "image/site/logo.png";
	break;
	
	default:
	return "image/site/logo.png";
	break;
	
	}
	
}



?>