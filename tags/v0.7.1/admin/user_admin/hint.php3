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

<font id=ac><b>�� NEW</b></font>

<p>
������ password�� �Է�. ������ ���� ������ �׳� ����θ� ��.
");
}


else if ($hintname == "language") {
  echo ("

<font id=ac><b>�� Language Choise</b></font>

<p>
�Խ����� Index�� menu, message���� �ѱ���� �Ұ�����
�������� �Ұ������� �����Ѵ�
");
}




else if ($hintname == "repass") {
  echo ("

<font id=ac><b>�� Re Pass</b></font>

<p>
password ����� ��Ÿ�� �����ϱ� ���ؼ� �ѹ��� �Է��� �Ͽ�
�ΰ��� �񱳽��� �����Ҷ� ������ ��Ŵ.
");
}




else if ($hintname == "scale") {
  echo ("

<font id=ac><b>�� Scale</b></font>

<p>
�� �������� ����� �� ���� ������ �����Ѵ�.
");
}


else if ($hintname == "namelenth") {
  echo ("

<font id=ac><b>�� NameLenth</b></font>

<p>
�̸� �ʵ��� �ִ� ���̸� �����Ѵ�.
");
}


else if ($hintname == "titlelenth") {
  echo ("

<font id=ac><b>�� TitleLenth</b></font>

<p>
���� �ʵ��� �ִ� ���̸� �����Ѵ�.
");
}


else if ($hintname == "tablewidth") {
  echo ("

<font id=ac><b>�� Size</b></font>

<p>
�Խ��� ���̺��� ���̸� �����Ѵ�.
");
}


else if ($hintname == "listguide") {
  echo ("

<font id=ac><b>�� ListGuide</b></font>

<p>
�� ����� �׵θ� ��� ������ �����Ѵ�.
");
}


else if ($hintname == "listguidefont") {
  echo ("

<font id=ac><b>�� ListGuideFont</b></font>

<p>
�۸���� �׵θ��� ���� ������ �����Ѵ�.
");
}


else if ($hintname == "listsubjbg") {
  echo ("

<font id=ac><b>�� ListSubjBG</b></font>

<p>
�� ����� �������� ��� ������ �����Ѵ�.
");
}


else if ($hintname == "listsubjfont") {
  echo ("

<font id=ac><b>�� ListSubjFont</b></font>

<p>
�� ����� �������� ���� ������ �����Ѵ�.
");
}


else if ($hintname == "listfieldbg") {
  echo ("

<font id=ac><b>�� ListFieldBG</b></font>

<p>
�� ����� �� �Խù� ���� ��� ������ �����Ѵ�.
");
}


else if ($hintname == "listfieldfont") {
  echo ("

<font id=ac><b>�� ListFieldFont</b></font>

<p>
�� ����� �� �Խù� ���� ���� ������ �����Ѵ�.
");
}


else if ($hintname == "listrebg") {
  echo ("

<font id=ac><b>�� ListReBG</b></font>

<p>
���ñ� ������ ��� ������ �����Ѵ�.
");
}


else if ($hintname == "listrefont") {
  echo ("

<font id=ac><b>�� ListReFont</b></font>

<p>
���ñ� ������ ���� ������ �����Ѵ�.
");
}


else if ($hintname == "viewguide") {
  echo ("

<font id=ac><b>�� ViewGuide</b></font>

<p>
�� ���� ���� �������� �׵θ� ������ �����Ѵ�.
");
}


else if ($hintname == "viewguidefont") {
  echo ("

<font id=ac><b>�� ViewGuideFont</b></font>

<p>
�� ���� ���� �������� �׵θ� ���� ������ �����Ѵ�.
");
}


else if ($hintname == "viewsubjbg") {
  echo ("

<font id=ac><b>�� ViewSubjBG</b></font>

<p>
�� ���� ���� �������� �� ���� ��� ������ �����Ѵ�.
");
}


else if ($hintname == "viewsubjfont") {
  echo ("

<font id=ac><b>�� ViewSubjFont</b></font>

<p>
�� ���� ���� �������� �� ���� ���� ������ �����Ѵ�.
");
}


else if ($hintname == "viewuserbg") {
  echo ("

<font id=ac><b>�� ViewUserBG</b></font>

<p>
�� ���� ���� �������� �۾��� ��� ������ �����Ѵ�.
");
}


else if ($hintname == "viewuserfont") {
  echo ("

<font id=ac><b>�� ViewUserFont</b></font>

<p>
�� ���� ���� �������� �۾��� ���� ������ �����Ѵ�.
");
}


else if ($hintname == "viewtextbg") {
  echo ("

<font id=ac><b>�� ViewTextBG</b></font>

<p>
�� ���� ���� �������� �� ���� ��� ������ �����Ѵ�.
");
}


else if ($hintname == "viewtextfont") {
  echo ("

<font id=ac><b>�� ViewTextFont</b></font>

<p>
�� ���� ���� �������� �� ���� ���� ������ �����Ѵ�.
");
}


else if ($hintname == "todayarticlebg") {
  echo ("

<font id=ac><b>�� TodayArticleBG</b></font>

<p>
�� ����Ʈ���� ��ȸ�� ���� ���� �ö�±� ǥ�� ��� ������ �����Ѵ�.
");
}


else if ($hintname == "mailtoadmin") {
  echo ("

<font id=ac><b>�� Reciever</b></font>

<p>
���� ��� �Ǿ����� ���Ϸ� ���� �������� ���� �ּҸ� �����Ѵ�. ���� ������ ���ϸ�
���� �߼� ��ü�� ���� �ʴ� ������ �����Ѵ�. ������ �ް� ���� �ʴٸ� ��ũ�� ���
�θ� �ȴ�.
");
}


else if ($hintname == "replywriter") {
  echo ("

<font id=ac><b>�� ReplyWriter</b></font>

<p>
�޴´ٸ� �������� ��� ���ñ��� ���拚 ������ �ø� ������� ���ñ��� 
������ ���Ϸ� ������.
");
}


else if ($hintname == "base") {
  echo ("

<font id=ac><b>�� Base</b></font>

<p>
�Խ����� ��ġ�� ����θ� ���´�. �Խ����� url��
http://domain/jsboard/list.php3?table=test ���
base�� http://domain/jsboard/ �� �ȴ�. �� �κ��� ���Ͽ� �����
�����̸�, ���Ͽ� �ش� url�� ���� ���� ���ؼ� �ʿ��ϴ�.
");
}


else if ($hintname == "upload") {
  echo ("

<font id=ac><b>�� File UPload ��뿩��</b></font>

<p>
����� �����ϸ� ���ȭ�� �� �Է� ȭ�� ���� �ø��� �κ��� ��Ÿ����.
");
}


else if ($hintname == "updir") {
  echo ("

<font id=ac><b>�� UP Directory</b></font>

<p>
upload�� file���� ����Ǵ� ���丮�� �����Ѵ�. �̴� ����η� �����ϸ�
�ظ��ϸ� default ���� �ǵ帮�� �ʴ� ���� ������ ����.
");
}


else if ($hintname == "filesize") {
  echo ("

<font id=ac><b>�� MAX File size</b></font>

<p>
upload�� ���Ǵ� file�� �ִ� size�� �����Ѵ�. �� �κ��� php.ini����
�ִ� 2M�� �����Ǿ� �����Ƿ� �� �̻��� �����Ϸ��� php.ini���� ������
�� ��� �Ѵ�.
");
}


else if ($hintname == "menuallow") {
  echo ("

<font id=ac><b>��  ��� Menu BAR ��뿩��</b></font>

<p>
���̺��� ������� �޴��ٰ� �Ʒ��� �־ �Ʒ����� page
�̵��� �Ͽ� �޴��ٸ� Ŭ���ϴ� ���� �����Ͽ� ������ �޴��ٰ�
���ü� �ֵ��� �Ѵ�. �� ����� no�� ���� ��� ���� ������
IP address�� ����� �ش�.
");
}

else if ($hintname == "useurl") {
  echo ("

<font id=ac><b>�� Homapage</b></font>

<p>
�Խ��ǿ� ���� ����Ҷ� Homapge�� ������ �ְ�����
������ ���� ������ ���θ� yes �� no �� ������ �Ѵ�.
");
}

else if ($hintname == "useemail") {
  echo ("

<font id=ac><b>�� E-mail</b></font>

<p>
�Խ��ǿ� ���� ����Ҷ� Email ������ �ְ�����
������ ���� ������ ���θ� yes �� no �� ������ �Ѵ�.
");
}

else if ($hintname == "writemode") {
  echo ("

<font id=ac><b>�� Write Mode</b></font>

<p>
�Խ��ǿ� ���� ���涧 admin�� ���� �ֵ��� �ϴ�
����� ���Ѵ�.
");
}

else  {
  echo ("

<font id=ac><b>�� ������</b></font>

<p>
ȣȪ �ƹ��͵� ����ϴ�.. ^^;;
");
}

?>

</font>

</td></tr>
</table>

</body>
</html>