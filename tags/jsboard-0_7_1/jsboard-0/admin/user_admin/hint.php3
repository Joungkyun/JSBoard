<?

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.2                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/

?>


<html>
<head>
<title>Admin HINT Page</title>

<style type="text/css">
a:link { text-decoration:none; color:white ; }
a:visited { text-decoration:none; color:white ; }
a:hover { color:red; }
td { font-size:9pt; color:#999999 }
 #td {font-size:9pt; color:#999999}
 #ac {font-size:9pt; color:white}
</style>

</head>

<body bgcolor=black>

<table border=0 width=100% height=100%>
<tr><td valign=top>

<font id=td>

<?

if ($hintname == "new") {
  echo ("

<font id=ac><b>♤ NEW</b></font>

<p>
변경할 password를 입력. 변경을 하지 않으면 그냥 비워두면 됨.
");
}


else if ($hintname == "language") {
  echo ("

<font id=ac><b>♤ Language Choise</b></font>

<p>
게시판의 Index나 menu, message들을 한국어로 할것인지
영문으로 할것인지를 결정한다
");
}




else if ($hintname == "repass") {
  echo ("

<font id=ac><b>♤ Re Pass</b></font>

<p>
password 변경시 오타를 방지하기 위해서 한번더 입력을 하여
두개를 비교시켜 동일할때 변경을 시킴.
");
}




else if ($hintname == "scale") {
  echo ("

<font id=ac><b>♤ Scale</b></font>

<p>
한 페이지에 출력을 할 글의 갯수를 지정한다.
");
}


else if ($hintname == "namelenth") {
  echo ("

<font id=ac><b>♤ NameLenth</b></font>

<p>
이름 필드의 최대 길이를 지정한다.
");
}


else if ($hintname == "titlelenth") {
  echo ("

<font id=ac><b>♤ TitleLenth</b></font>

<p>
제목 필드의 최대 길이를 지정한다.
");
}


else if ($hintname == "tablewidth") {
  echo ("

<font id=ac><b>♤ Size</b></font>

<p>
게시판 테이블의 넓이를 지정한다.
");
}


else if ($hintname == "listguide") {
  echo ("

<font id=ac><b>♤ ListGuide</b></font>

<p>
글 목록의 테두리 배경 색상을 지정한다.
");
}


else if ($hintname == "listguidefont") {
  echo ("

<font id=ac><b>♤ ListGuideFont</b></font>

<p>
글목록의 테두리의 글자 색상을 지정한다.
");
}


else if ($hintname == "listsubjbg") {
  echo ("

<font id=ac><b>♤ ListSubjBG</b></font>

<p>
글 목록의 제목줄의 배경 색상을 지정한다.
");
}


else if ($hintname == "listsubjfont") {
  echo ("

<font id=ac><b>♤ ListSubjFont</b></font>

<p>
글 목록의 제목줄의 글자 색상을 지정한다.
");
}


else if ($hintname == "listfieldbg") {
  echo ("

<font id=ac><b>♤ ListFieldBG</b></font>

<p>
글 목록의 각 게시물 제목 배경 색상을 지정한다.
");
}


else if ($hintname == "listfieldfont") {
  echo ("

<font id=ac><b>♤ ListFieldFont</b></font>

<p>
글 목록의 각 게시물 제목 글자 색상을 지정한다.
");
}


else if ($hintname == "listrebg") {
  echo ("

<font id=ac><b>♤ ListReBG</b></font>

<p>
관련글 제목의 배경 색상을 지정한다.
");
}


else if ($hintname == "listrefont") {
  echo ("

<font id=ac><b>♤ ListReFont</b></font>

<p>
관련글 제목의 글자 색상을 지정한다.
");
}


else if ($hintname == "viewguide") {
  echo ("

<font id=ac><b>♤ ViewGuide</b></font>

<p>
글 내용 보기 페이지의 테두리 색상을 지정한다.
");
}


else if ($hintname == "viewguidefont") {
  echo ("

<font id=ac><b>♤ ViewGuideFont</b></font>

<p>
글 내용 보기 페이지의 테두리 글자 색상을 지정한다.
");
}


else if ($hintname == "viewsubjbg") {
  echo ("

<font id=ac><b>♤ ViewSubjBG</b></font>

<p>
글 내용 보기 페이지의 글 제목 배경 색상을 지정한다.
");
}


else if ($hintname == "viewsubjfont") {
  echo ("

<font id=ac><b>♤ ViewSubjFont</b></font>

<p>
글 내용 보기 페이지의 글 제목 글자 색상을 지정한다.
");
}


else if ($hintname == "viewuserbg") {
  echo ("

<font id=ac><b>♤ ViewUserBG</b></font>

<p>
글 내용 보기 페이지의 글쓴이 배경 색상을 지정한다.
");
}


else if ($hintname == "viewuserfont") {
  echo ("

<font id=ac><b>♤ ViewUserFont</b></font>

<p>
글 내용 보기 페이지의 글쓴이 글자 색상을 지정한다.
");
}


else if ($hintname == "viewtextbg") {
  echo ("

<font id=ac><b>♤ ViewTextBG</b></font>

<p>
글 내용 보기 페이지의 글 내용 배경 색상을 지정한다.
");
}


else if ($hintname == "viewtextfont") {
  echo ("

<font id=ac><b>♤ ViewTextFont</b></font>

<p>
글 내용 보기 페이지의 글 내용 글자 색상을 지정한다.
");
}


else if ($hintname == "todayarticlebg") {
  echo ("

<font id=ac><b>♤ TodayArticleBG</b></font>

<p>
글 리스트에서 조회수 옆에 오늘 올라온글 표시 배경 색상을 지정한다.
");
}


else if ($hintname == "mailtoadmin") {
  echo ("

<font id=ac><b>♤ Reciever</b></font>

<p>
글이 등록 되었을때 메일로 받을 관리자의 메일 주소를 지정한다. 만약 지정을 안하면
메일 발송 자체를 하지 않는 것으로 간주한다. 메일을 받고 싶지 않다면 블랭크로 비어
두면 된다.
");
}


else if ($hintname == "replywriter") {
  echo ("

<font id=ac><b>♤ ReplyWriter</b></font>

<p>
받는다를 선택했을 경우 관련글을 남길떄 원글을 올린 사람에게 관련글의 
내용을 메일로 보낸다.
");
}


else if ($hintname == "base") {
  echo ("

<font id=ac><b>♤ Base</b></font>

<p>
게시판이 설치된 웹경로를 적는다. 게시판의 url이
http://domain/jsboard/list.php3?table=test 라면
base는 http://domain/jsboard/ 가 된다. 이 부분은 메일에 관계된
내용이며, 메일에 해당 url을 남겨 놓기 위해서 필요하다.
");
}


else if ($hintname == "upload") {
  echo ("

<font id=ac><b>♤ File UPload 사용여부</b></font>

<p>
허용을 선택하면 목록화면 및 입력 화면등에 파일 올리기 부분이 나타난다.
");
}


else if ($hintname == "updir") {
  echo ("

<font id=ac><b>♤ UP Directory</b></font>

<p>
upload한 file들이 저장되는 디렉토리를 설정한다. 이는 상대경로로 기입하며
왠만하면 default 값을 건드리지 않는 것이 좋을것 같다.
");
}


else if ($hintname == "filesize") {
  echo ("

<font id=ac><b>♤ MAX File size</b></font>

<p>
upload시 허용되는 file의 최대 size를 지정한다. 이 부분은 php.ini에서
최대 2M로 지정되어 있으므로 이 이상을 지정하려면 php.ini에서 수정을
해 줘야 한다.
");
}


else if ($hintname == "menuallow") {
  echo ("

<font id=ac><b>♤  상단 Menu BAR 사용여부</b></font>

<p>
테이블이 길어질때 메뉴바가 아래에 있어서 아래까지 page
이동을 하여 메뉴바를 클릭하는 것을 보완하여 위에도 메뉴바가
나올수 있도록 한다. 이 기능을 no로 했을 경우 원격 유저의
IP address를 출력해 준다.
");
}

else if ($hintname == "useurl") {
  echo ("

<font id=ac><b>♤ Homapage</b></font>

<p>
게시판에 글을 등록할때 Homapge를 적을수 있게할지
적을수 없게 할지의 여부를 yes 와 no 로 결정을 한다.
");
}

else if ($hintname == "useemail") {
  echo ("

<font id=ac><b>♤ E-mail</b></font>

<p>
게시판에 글을 등록할때 Email 적을수 있게할지
적을수 없게 할지의 여부를 yes 와 no 로 결정을 한다.
");
}

else if ($hintname == "writemode") {
  echo ("

<font id=ac><b>♤ Write Mode</b></font>

<p>
게시판에 글을 남길때 admin만 쓸수 있도록 하는
기능을 말한다.
");
}

else  {
  echo ("

<font id=ac><b>♤ 공사중</b></font>

<p>
호홋 아무것도 없답니다.. ^^;;
");
}

?>

</font>

</td></tr>
</table>

</body>
</html>