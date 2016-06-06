<?

/************************************************************************
*                                                                       *
*                  OOPS Administration center v1.2                      *
*                     Scripted by JoungKyun Kim                         *
*                 admin@oops.org http://www.oops.org                    *
*                                                                       *
************************************************************************/


/******************************************
             user board 관리자 password
 *****************************************/

/* 게시판 관리자의 패스워드를 지정 */
$board_manager  = "ooK/oSLfDJOUI" ;


/******************************************
         Table 기본 informations            
 *****************************************/

$pern      = "10" ;	// 페이지당 게시물
$namel     = "8" ;	// 이름 필드의 최대 길이
$titll     = "40" ;	// 제목 필드의 최대 길이
$width     = "550" ;	// 게시판 너비


/******************************************
         List page 색상            
 *****************************************/

$l0_bg     = "#a5b5c5" ; // 글목록 테두리
$l0_fg     = "#ffffff" ; // 글목록 테두리 글자
$l1_bg     = "#a5c5c5" ; // 글목록 제목줄 배경
$l1_fg     = "#ffffff" ; // 글목록 제목줄 글자
$l2_bg     = "#ffffff" ; // 글목록 보통글 배경
$l2_fg     = "#555555" ; // 글목록 보통글 글자
$l3_bg     = "#dcdcdc" ; // 글목록 답장글 배경
$l3_fg     = "#555555" ; // 글목록 답장글 글자


/******************************************
         내용 보기 page 색상            
 *****************************************/

$r0_bg     = "#a5b5c5" ; // 글보기 테두리
$r0_fg     = "#ffffff" ; // 글보기 테두리 글자
$r1_bg     = "#a5c5c5" ; // 글보기 제목줄 배경
$r1_fg     = "#ffffff" ; // 글보기 제목줄 글자
$r2_bg     = "#dcdcdc" ; // 글보기 사용자 배경
$r2_fg     = "#555555" ; // 글보기 사용자 글자
$r3_bg     = "#ffffff" ; // 글보기 글내용 배경
$r3_fg     = "#555555" ; // 글보기 글내용 글자

$t0_bg     = "#778899" ; // 오늘 올라온 글 표시


/******************************************
         Menu 정보
 *****************************************/

// menu를 보여줄지 허용여부
$menuallow	= "no" ;

// menu를 보여줄시에 home dirctory의 link
$homelink	= "" ;

// menu를 보여줄 시에 list page의 link
$backlink	= "javascript:history.back()" ;


/******************************************
         기타 정보            
 *****************************************/

// yes를 선택하면 목록화면 및 입력 등에 파일 올리기 나옴
$file_upload = "no" ;

// 파일 업로드 디렉토리
$filesavedir = "./include/$table/files" ;

//최대 파일 사이즈. 이건 php3.ini 에서 조정해야함 기본 2M.
$maxfilesize = "2000000" ;

// 메일주소를 적으면 글이 올라올 때 메일로 보내준다.
$mailtoadmin = "" ;

// 답장을 쓸 때 원래 글을 쓴 사람에게 메일이 보내진다.
$mailtowriter = "no" ;

// 게시판이 설치된 TOP 디렉토리
$bbshome = "" ;

// Board version (건들지 마시오)
$webboard_version = "0.6pre2" ;

// 홈페이지 등록 여부
$use_url = "yes" ;

// 전자우편주소 등록 여부
$use_email   = "yes" ;

?>
