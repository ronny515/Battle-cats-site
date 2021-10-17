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
	

    $query = "SELECT `id`,`name` FROM enemy ORDER BY `id` ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id,$name);
	$num_rows=$stmt->num_rows;


    echo "<p>Number of items found: ".$num_rows."</p>";
	
	//換頁物件
	$PageMenu = new Page();
	$PageMenu->query="SELECT MAX(`id`) FROM enemy";
	$PageMenu->WhichPage="enemy";//連結頁面參數
	if(isset($_GET['page']))$page=(int)$_GET['page'];
	else $page=1;
	$PageMenu->PageQuery();//執行SQL查詢
	$PageMenu->PageCount();//計算最大頁
	$PageMenu->ShowPageMenu($page);//顯示選單，$page為當頁
	/*
    $query = "SELECT MAX(`id`) FROM enemy";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($max_cat_id);
	$stmt->fetch();
	$num_rows=$max_cat_id;
	//echo "<p>最大貓".$num_rows."</p>";
	//換頁
	//每頁數量
	$row_p_page=10;
	//計算頁數
	$total_page=(int)($num_rows/$row_p_page);
	if($num_rows%$row_p_page)$total_page++;
	if(isset($_GET['page']))$page=(int)$_GET['page'];
	else $page=1;
	*/
	
	
	if($page!="all"){
		$page_start=$PageMenu->row_p_page*($page-1);
		$page_end=$PageMenu->row_p_page*($page);
		$query = "SELECT `id`,`name` FROM enemy WHERE `id` > ? AND `id` <= ?  ORDER BY `id` ASC";
		//$query = "SELECT `id`,`cat_id`,`form`,`name` FROM enemy WHERE `cat_id` > ? ORDER BY `cat_id` ASC,`form` ASC LIMIT ?";
		//$query = "SELECT `id`,`cat_id`,`form`,`name` FROM enemy ORDER BY `cat_id` ASC,`form` ASC LIMIT ?,?";
		$stmt = $db->prepare($query);
		$stmt->bind_param('ss',$page_start,$page_end);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id,$name);
		$num_rows=$stmt->num_rows;
	}
	
	/*
	//換頁選單
	echo "頁次 <a href=\"enemy.php?page=1\">&lt&lt</a> ";
	$page1=$page-4;
	$page2=$page+4;
	if($page-4<=0){
		$page1=1;
		$page2=9;
	}	
	if($total_page-$page<=4){
		$page1=$total_page-8;
		$page2=$total_page;
	}
	for ($i=$page1;$i<=$page2;$i++){
		//if($i<$page && $page-$i<=4)echo "  <a href=\"enemy.php?page=".$i."\">".$i."</a> ";
		if($i==$page)echo " ".$i." ";
		else echo "  <a href=\"enemy.php?page=".$i."\">".$i."</a> ";
		//if($i>$page && $i-$page<=4 && $i<=$total_page)echo "  <a href=\"enemy.php?page=".$i."\">".$i."</a> ";
	}
	echo "  <a href=\"enemy.php?page=".$total_page."\">&gt&gt</a> ";
	echo "  <a href=\"enemy.php?page=all\">全部all</a> ";
	echo "<br>";
	*/
	
	if($num_rows==0)echo "查無資料";
		
	if($page=="all"){
    $query = "SELECT `id`,`name` FROM enemy ORDER BY `id` ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id,$name);
	$num_rows=$stmt->num_rows;
	}
	//換頁結束
	echo "<table  class=\"table-bordered table-hover\">\n";
	$i=1 ;
    while($stmt->fetch()) {
		$query2 = "SELECT * FROM `ename_trans` WHERE `id` = ? ";
		$stmt2 = $db->prepare($query2);
		$stmt2->bind_param('s',$id);
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
		
		

	    echo "<tr>";
		echo "<td>".$id." ";
		echo "  <a href=\"enemy1.php?id=".$id."\">".$name_tw."<br>(".$name.")";
		echo "</a><img src=\"".get_img_e($id)."\" alt=\"\"/></td></tr>\n";
		
		$stmt2->free_result();
    }

echo "  
</table>";
$PageMenu->ShowPageMenu($page);
    
    $stmt->free_result();
	$db->close();




include('./template/footer.html');
?>