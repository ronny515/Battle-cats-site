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
	




    $query = "SELECT `id`,`cat_id`,`form`,`name` FROM catunit_j ORDER BY `cat_id` ASC,`form` ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id,$cat_id,$form,$name);
	$num_rows=$stmt->num_rows;
//,$useskill , $catdescribe , $howget , $combo , $version

    echo "<p>Number of items found: ".$num_rows."</p>";
	
	//換頁開始
	//換頁物件
	$PageMenu = new Page();
	$PageMenu->query="SELECT MAX(`cat_id`) FROM catunit_j";
	$PageMenu->WhichPage="catsuni";//連結頁面參數
	if(isset($_GET['page']))$page=(int)$_GET['page'];
	else $page=1;
	$PageMenu->PageQuery();//執行SQL查詢
	$PageMenu->PageCount();//計算最大頁
	$PageMenu->ShowPageMenu($page);//顯示選單，$page為當頁
	
	/*
    $query = "SELECT MAX(`cat_id`) FROM catunit_j";
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
	$total_page=(int)($num_rows/$row_p_page);//總頁
	if($num_rows%$row_p_page)$total_page++;
	*/
	
	
	if(isset($_GET['page']))$page=(int)$_GET['page'];
	else $page=1;
	
	if($page!="all"){
		$page_start=$PageMenu->row_p_page*($page-1);
		$page_end=$PageMenu->row_p_page*($page-1)+$PageMenu->row_p_page;
		
		$query = "SELECT `id`,`cat_id`,`form`,`name` FROM catunit_j WHERE `cat_id` > ? AND `cat_id` <= ?  ORDER BY `cat_id` ASC,`form` ASC";
		//$query = "SELECT `id`,`cat_id`,`form`,`name` FROM catunit_j WHERE `cat_id` > ? ORDER BY `cat_id` ASC,`form` ASC LIMIT ?";
		//$query = "SELECT `id`,`cat_id`,`form`,`name` FROM catunit_j ORDER BY `cat_id` ASC,`form` ASC LIMIT ?,?";
		$stmt = $db->prepare($query);
		$stmt->bind_param('ss',$page_start,$page_end);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id,$cat_id,$form,$name);
		$num_rows=$stmt->num_rows;
	}
	/*
	//換頁選單
	echo "頁次 <a href=\"catsuni.php?page=1\">&lt&lt</a> ";
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
		if($i==$page)echo " ".$i." ";
		else echo "  <a href=\"catsuni.php?page=".$i."\">".$i."</a> ";
	}
	
	echo "  <a href=\"catsuni.php?page=".$total_page."\">&gt&gt</a> ";
	echo "  <a href=\"catsuni.php?page=all\">全部all</a> ";
	echo "<br>";
	*/
	
	
	if($num_rows==0)echo "查無資料";
	if($page=="all"){
    $query = "SELECT `id`,`cat_id`,`form`,`name` FROM catunit_j ORDER BY `cat_id` ASC,`form` ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id,$cat_id,$form,$name);
	$num_rows=$stmt->num_rows;
	}
	//換頁結束
	
	
	echo "
	<table  class=\"table-bordered table-hover\">
	  
		<tr>";
	$i=1 ;
    while($stmt->fetch()) {
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
		
		
		//if($cat_id==10)return;
		if  ($i<=$form) {
		    echo "<td>".$cat_id."-".$form;
			echo "  <a href=\"cats1.php?catid=".$id."\">".$name_tw."<br>(".$name.")";
			echo "</a><img src=\"".get_img($cat_id,$form)."\" alt=\"\"/></td>";
			$i++;
		}
		else{
		$i=1;
	    echo "</tr>
				<tr>";
		  echo "<td>".$cat_id."-".$form;
		echo "  <a href=\"cats1.php?catid=".$id."\">".$name_tw."<br>(".$name.")";
		echo "</a><img src=\"".get_img($cat_id,$form)."\" alt=\"\"/></td>";
		}
		$stmt2->free_result();
    }

echo "</tr>
  
</table>";

    
    $stmt->free_result();
	$db->close();
$PageMenu->ShowPageMenu($page);//顯示選單，$page為當頁



include('./template/footer.html');
?>