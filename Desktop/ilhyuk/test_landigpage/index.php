<?php
    $cm_dir_path = $_SERVER['DOCUMENT_ROOT']; // 서버 디렉토리 경로 반환
    require_once "./db_common.php"; // 지정된 파일을 한번만 실행? 중복방지
    $now_datetime = new DateTime(); // 날짜시간 객체 생성
    $now_YmdHis = $now_datetime -> format('Y-m-d H:i:s'); //날짜 시간 문자열로 변환

    // 정렬 세팅
    $search_field = $_GET['search_field']; // 검색필드
    $search_text = $_GET['search_text'];    // 검색단어
    $sort_field = $_GET['sort_field'];  // 정렬필드
    $sort_value = $_GET['sort_value'];  // 정렬기준
    $won_sort_value = $_GET['won_sort_value']; // 정렬기준
    //$_GET 슈퍼글로벌, 배열을 사용하여 데이터 수신

    if(empty($sort_field)){ //비어있는지 확인
        $sort_value = "desc"; 
        $next_sort_value = "asc";
        $sort_field = "seq";
        $won_sort_value = "seq";
        // 변수 초기화
        // desc(내림차순), asc(오름차순), seq(정렬할 열 이름을 의미?)
		// 각 카테고리 마다 오름, 내림차순 정렬을 의미?
    }else{ 
        if($sort_field == $won_sort_value){
            if(empty($sort_value)){
                $sort_value = "desc";
                $next_sort_value = "asc";
            }else if($sort_value == "desc"){
                $next_sort_value = "asc";
            }else{
                $next_sort_value = "desc";
            }
        }else{
            $sort_value = "desc";
            $next_sort_value = "asc";
        }
		
        $won_sort_value = $sort_field;
    }
    // 정렬 값이 지정됨? 
    $order_by = " order by ".$sort_field." ".$sort_value; //정렬할열지정, 정렬순서지정
    // 문자열을 생성, 두개 값을 데이터 정렬

    //검색조건 쿼리 
    $where_query = ""; //검색조건 변수 지정
	// stat 검색어
	$search_stat = $_GET['search_stat']; // search_stat 변수를 받아와서 $search_stat에 저장
	if(!empty($search_stat)){
		$where_query .= "and stat like '%".$search_stat."%'"; // . -> 문자열 연결 / !empty($search_stat) 비어있는지 확인후 없으면 관련된거 검색해서 결과값 나옴?
	}
	// ads_div 검색어
	$search_ads_div = $_GET['search_ads_div'];
	if(!empty($search_ads_div)){
		$where_query .= " and ads_div like '%".$search_ads_div."%'";
	}
	// reg_date 검색어
	$search_reg_date = $_GET['search_reg_date'];
	if(!empty($search_reg_date)){
		$where_query .= " and regdate like '%".$search_reg_date."%'";
	}
	// ads_id 검색어
	$search_ads_id = $_GET['search_ads_id'];
	if(!empty($search_ads_id)){
		$where_query .= " and ads_id like '%".$search_ads_id."%'";
	}
	// company 검색어
	$search_company = $_GET['search_company'];
	if(!empty($search_company)){
		$where_query .= " and company like '%".$search_company."%'";
	}
	// address 검색어
	$search_address = $_GET['search_address'];
	if(!empty($search_address)){
		$where_query .= " and address like '%".$search_address."%'";
	}
	// aw_id 검색어
	$search_aw_id = $_GET['search_aw_id'];
	if(!empty($search_aw_id)){
		$where_query .= " and aw_id like '%".$search_aw_id."%'";
	}
	// send_to 검색어
	$search_send_to = $_GET['search_send_to'];
	if(!empty($search_send_to)){
		$where_query .= " and send_to like '%".$search_send_to."%'";
	}
	// memo 검색어
	$search_memo = $_GET['search_memo'];
	if(!empty($search_memo)){
		$where_query .= " and memo like '%".$search_memo."%'";
	}
	// description 검색어
	$search_description = $_GET['search_description'];
	if(!empty($search_description)){
		$where_query .= " and description like '%".$search_description."%'";
	}
	// is_address 검색어
	$search_is_address = $_GET['search_is_address'];
	if(!empty($search_is_address)){
		$where_query .= " and is_address like '%".$search_is_address."%'";
	}
	// is_agency 검색어
	$search_is_agency = $_GET['search_is_agency'];
	if(!empty($search_is_agency)){
		$where_query .= " and is_agency like '%".$search_is_agency."%'";
	}
	// 데이터추출 및 배열
	$select_query = "SELECT * FROM `landing-list` where 1=1 ".$where_query.$order_by; 
	// 				landin-list 모든열을 선택		항상 참인 조건을 추가		변수들을 (. -> 연결) 연결하여 	$select_query대입한다는말
	$result_query = querySelect($select_query);
	//				쿼리 셀렉트에 셀렉트 쿼리를 받아서 result 쿼리에 반환 한다는것
?>
<html>
	<head>
		<title>test_랜딩페이지 검색시스템</title>
		<!-- <link href="<?=$cm_dir_path?>/cm/config/default.css" rel="stylesheet"> -->
		<link href="./default.css" rel="stylesheet">  
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<style>
			span.sub_info {
				color: gray;
				font-size: 10px;
			}
			span.sub_info2 {
				color: red;
				font-size: 13px;
			}
			br.clear{clear:both}
			td{font-size:15px;}
		</style>
	</head>
<script language="javascript">

// 페이지 정렬 필드 세팅함수
function pageMove(field,values){ // 페이지 무브로 필드와 벨류변수를 받는다
	f = document.searchForm; // f변수에 폼객체를 할당
	f.sort_field.value = field; // 폼객체 안에서 field 값을 할당
	f.sort_value.value = values; // 폼객체 안에서 value 값을 할당
	f.submit(); // 폼객체를 submit 서버에 보낸다
}
// 움직임 없을때 내용삭제 
let idleTime = 0;
var idleInterval = "";
$(document).ready(function () {
	// 1분마다 일정시간 움직임이 있으면 초기화
	idleInterval = setInterval(timerIncrement, 60000); 
	$(this).mousemove(function (e) {  //마우스 
		idleTime = 0; 
	});
	$(this).keypress(function (e) { //키보드
		idleTime = 0; 
	});
});

function timerIncrement() {
	idleTime = idleTime + 1;
	// 5분이상 움직임이 없으면 새로고침 & 경로이동
	//console.log(idleTime);
	if (idleTime > 5) { 
		$("body").html("<div style='text-align:center;font-size:40px;'>screen saver </div>");
		clearInterval(idleInterval);
	}// 아무런 움직임이 보이지 않을때 "screen saver" 라는 보호메시지만 뜨게 나옴
}

</script>
	<body>
		<form name="searchForm" method="get" action="<?=$PHP_SELF?>">
			<input type="hidden" name="sort_field" value="<?=$sort_field?>">  
			<input type="hidden" name="won_sort_value" value="<?=$won_sort_value?>">
			<input type="hidden" name="sort_value" value="<?=$sort_value?>">
		<table style="float: left;">
			<thead>
				<tr style="display:none">
					<td colspan=15 style="text-align:center;padding:5px 10px;">
						<button style="background: #fff;border: 1px solid #000;padding:3px 5px;">검색</button>
					</td>
				</tr>
				<tr>
					<td width="50">&nbsp;</td>
					<td width="50">&nbsp;</td>
					<td width="110">
						<div style="padding:5px;">
							<input type="text" style="border:1px solid #000000;padding:5px;width:80%;text-align:center;" name="search_reg_date" value="<?=$search_reg_date?>">
						</div>
					</td>
					<td width="80">
						<div style="padding:5px;">
							<input type="text" style="border:1px solid #000000;padding:5px;width:80%;text-align:center;" name="search_stat" value="<?=$search_stat?>">
						</div>
						<a href="javascript:pageMove('stat','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "stat") echo "red"; else echo "black"; ?>">
					</td>
					<td width="70">
						<div style="padding:5px;">
							<input type="text" style="border:1px solid #000000;padding:5px;width:80%;text-align:center;" name="search_ads_div" value="<?=$search_ads_div?>">
						</div>
					</td>
					<td width="100">
						<div style="padding:5px;">
							<input type="text" style="border:1px solid #000000;padding:5px;width:80%;text-align:center;" name="search_ads_id" value="<?=$search_ads_id?>">
						</div>
					</td>
					<td width="100">
						<div style="padding:5px;">
							<input type="text" style="border:1px solid #000000;padding:5px;width:80%;text-align:center;" name="search_company" value="<?=$search_company?>">
						</div>
					</td>
					<td width="250">
						<div style="padding:5px;">
							<input type="text" style="border:1px solid #000000;padding:5px;width:80%;text-align:center;" name="search_address" value="<?=$search_address?>">
						</div>
					</td>
					<td width="100">
						<div style="padding:5px;">
							<input type="text" style="border:1px solid #000000;padding:5px;width:80%;text-align:center;" name="search_aw_id" value="<?=$search_aw_id?>">
						</div>
					</td>
					<td width="100">
						<div style="padding:5px;">
							<input type="text" style="border:1px solid #000000;padding:5px;width:80%;text-align:center;" name="search_send_to" value="<?=$search_send_to?>">
						</div>
					</td>
					<td width="200">
						<div style="padding:5px;">
							<input type="text" style="border:1px solid #000000;padding:5px;width:80%;text-align:center;" name="search_firebase" value="<?=$search_firebase?>">
						</div>
					</td>
					<td width="200">
						<div style="padding:5px;">
							<input type="text" style="border:1px solid #000000;padding:5px;width:80%;text-align:center;" name="search_memo" value="<?=$search_memo?>">
						</div>
					</td>
					<td width="100">
						<div style="padding:5px;">
							<input type="text" style="border:1px solid #000000;padding:5px;width:80%;text-align:center;" name="search_description" value="<?=$search_description?>">
						</div>
					</td>
					<td width="100">
						<div style="padding:5px;">
							<input type="text" style="border:1px solid #000000;padding:5px;width:80%;text-align:center;" name="search_is_address" value="<?=$search_is_address?>">
						</div>
					</td>
					<td width="100">
						<div style="padding:5px;">
							<input type="text" style="border:1px solid #000000;padding:5px;width:80%;text-align:center;" name="search_is_agency" value="<?=$search_is_agency?>">
						</div>
					</td>
				</tr>
				<tr>
					<td width="50">수정</td>
					<td width="30">
						<a href="javascript:pageMove('seq','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "seq") echo "red"; else echo "black"; ?>">seq</a>
					</td>
					<td width="110">
						<a href="javascript:pageMove('regdate','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "regdate") echo "red"; else echo "black"; ?>">REGDATE</a>
					</td>
					<td width="80">
						<a href="javascript:pageMove('stat','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "stat") echo "red"; else echo "black"; ?>">STAT
					</td>
					<td width="70">
						<a href="javascript:pageMove('ads_div','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "ads_div") echo "red"; else echo "black"; ?>">ADS_DIV
					</td>
					<td width="100">
						<a href="javascript:pageMove('ads_id','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "ads_id") echo "red"; else echo "black"; ?>">ADS_ID
					</td>
					<td width="100">
						<a href="javascript:pageMove('company','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "company") echo "red"; else echo "black"; ?>">COMPANY
					</td>
					<td width="250">
						<a href="javascript:pageMove('address','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "address") echo "red"; else echo "black"; ?>">ADDRESS
					</td>
					<td width="100">
						<a href="javascript:pageMove('aw_id','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "aw_id") echo "red"; else echo "black"; ?>">AW_ID
					</td>
					<td width="100">
						<a href="javascript:pageMove('send_to','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "send_to") echo "red"; else echo "black"; ?>">SEND_TO
					</td>
					<td width="100">
						<a href="javascript:pageMove('firebase','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "firebase") echo "red"; else echo "black"; ?>">FIREBASE
					</td>
					<td width="100">
						<a href="javascript:pageMove('memo','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "memo") echo "red"; else echo "black"; ?>">MEMO
					</td>
					<td width="100">
						<a href="javascript:pageMove('description','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "description") echo "red"; else echo "black"; ?>">DESCRIPTION
					</td>
					<td width="100">
						<a href="javascript:pageMove('is_address','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "is_address") echo "red"; else echo "black"; ?>">IS_ADDRESS
					</td>
					<td width="100">
						<a href="javascript:pageMove('is_agency','<?=$next_sort_value?>');void(0);"><font style="color:<?php if($sort_field == "is_agency") echo "red"; else echo "black"; ?>">IS_AGENCY
					</td>
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach ($result_query as $idx => $land_list) { //foreach 루프시작 - 배열 각항목 반복 as 값을 저장할 변수 => 변수 (항목)
						$seq			= $land_list['seq'];
						$regdate		= $land_list['regdate'];
						$stat			= $land_list['stat'];
						$ads_div		= $land_list['ads_div'];
						$ads_id			= $land_list['ads_id'];
						$company		= $land_list['company'];
						$address		= $land_list['address'];
						$aw_id			= $land_list['aw_id'];
						$send_to		= $land_list['send_to'];
						$firebase		= $land_list['firebase'];
						$memo			= $land_list['memo'];
						$description	= $land_list['description'];
						$is_id_correct	= $land_list['is_id_correct'];
						$is_send_correct= $land_list['is_send_correct'];
						$prcd_date		= $land_list['prcd_date'];
						$is_address		= $land_list['is_address'];
						$is_agency		= $land_list['is_agency'];

						if(empty($is_address)) $is_address = "없음"; //$is_address 비어 있으면 없음으로 설정
						if($ads_div == "파워링크"){
							if($is_agency == "-") $is_agency = "없음";
							// $ads_div가 파워링크, $is_agency 가 "-" (없다는표시?) 없음으로 설정
						}
						echo "<tr>";
						echo "<td style=\"text-align:center\"><a href='https://test.e-e.kr/adminer.php?server=localhost&username=root&db=landing-list&edit=landing-list&where%5Bseq%5D=".$seq."' target='_blank'>수정</a></td>";
						echo "<td style=\"text-align:center\">".$seq."</td>";
						echo "<td style=\"text-align:center\">".date('m-d', strtotime($regdate));"</td>";
						echo "<td style=\"text-align:center\">".$stat."</td>";					
						echo "<td style=\"text-align:center\">".$ads_div."</td>";
						echo "<td style=\"text-align:center\">".$ads_id."</td>";
						echo "<td style=\"text-align:center\">".$company."</td>";
						echo "<td style=\"text-align:left;padding-left:10px;\"><a href='".$address."' target='_blank'>".$address."</td>";
						echo "<td style=\"text-align:center\">".$aw_id."</td>";
						echo "<td style=\"text-align:center\">".$send_to."</td>";
						echo "<td style=\"text-align:center\">".$firebase."</td>";
						echo "<td style=\"text-align:center\">".$memo."</td>";
						echo "<td style=\"text-align:center\">".$description."</td>";
						echo "<td style=\"text-align:center\">".$is_address."</td>";
						echo "<td style=\"text-align:center\">".$is_agency."</td>";
						echo "</tr>";
					} //행과 열을 출력 -> 각열과 행에는 가져온 값으로 채워짐
				?>
			</tbody>
		</table>
		<br class="clear">
		<span class="sub_info2">
		</span>
	</form>
	</body>
</html>
