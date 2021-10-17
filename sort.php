<?
include('./func/function.php');
include('./template/header.html');
header("Content-Type:text/html; charset=utf-8");
include('./menu.php');
    @$db = new mysqli($dbhost, $dbuser, $dbpw , $dbname);
	@$db -> set_charset("utf8");
	
    if (mysqli_connect_errno()) {
		echo "b";
		echo "<p>eeError: Could not connect to database.<br/>
				 Please try again later.</p>";
		exit;
    }
	
class SortMenu {
	// Properties
	public $SortTitle;
	public $SortType;
	public $WordNum;
	
	function ShowTitle() {
		echo "<h5>".$this->SortTitle."</h5>\n";
		//$this->title = $this->SortMenu.$SortTitle;
	}
	
	function ShowMenu() {
		for ($i=0;$i<((count($this->WordNum,1)-count($this->WordNum))/2);$i++){
			echo "<a href=\"cats.php?".$this->SortType."=".$this->WordNum[0][$i]."\">".$this->WordNum[1][$i]."</a>\t\n";
		}
		echo "<br>";
	}
	
	function ShowImage() {
		$url="image/sort/".$this->$SortType.$this->$WordNum[0][$i].".png";
		echo "<img src=\"".$url."\" alt=\"\"/>";
	}
  
}

$EventSort = new SortMenu();
$EventSort->SortTitle="活動分類";
$EventSort->SortType="event";
$EventSort->WordNum=array(array(999),
			array('亂馬1/2'));

$BasicSort = new SortMenu();
$BasicSort->SortTitle="貓咪分類";
$BasicSort->SortType="type";
$BasicSort->WordNum=array(array(1,2,3,4,5,6),
			array('基本','EX','稀有','激稀有','超激稀有','傳說稀有'));

$EnemySort = new SortMenu();
$EnemySort->SortTitle="對敵人分類";
$EnemySort->SortType="enemy";
$EnemySort->WordNum=array(array(1,2,4,8,16,32,64,128,256),
			array('紅色','漂浮','黑色','鋼鐵','天使','異星','不死','古代','白色'));
			
$EffectSort = new SortMenu();
$EffectSort->SortTitle="技能效果分類";
$EffectSort->SortType="effect";
$EffectSort->WordNum=array(array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,35,38,39,201,202,203,301),
			array('攻擊力下降','動作停止','動作變慢','只能攻擊','善於攻擊','很耐打','超級耐打','超大傷害','極度傷害','打飛',
			'傳送','詛咒','攻擊無效','攻擊力上升','死前存活','善於攻城','會心一擊','終結不死','破壞護頓','混沌一擊',
			'得到很多錢','鋼鐵','小波動','波動','烈波攻擊','無效','波動滅止','遠距','全方位攻擊','連續攻擊',
			'魔女殺手','使徒殺手','一次攻擊'));
	
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

$EventSort->ShowTitle();
$EventSort->ShowMenu();
echo "<br>";
$BasicSort->ShowTitle();
$BasicSort->ShowMenu();
echo "<br>";
$EnemySort->ShowTitle();
$EnemySort->ShowMenu();
echo "<br>";
$EffectSort->ShowTitle();
$EffectSort->ShowMenu();
echo "<br>";

include('./template/sort.html');
include('./template/footer.html');
?>