<?

/************************************************************************
*                                                                       *
*                  OOPS Administration center v1.2                      *
*                     Scripted by JoungKyun Kim                         *
*                 admin@oops.org http://www.oops.org                    *
*                                                                       *
************************************************************************/


/******************************************
             user board ������ password
 *****************************************/

/* �Խ��� �������� �н����带 ���� */
$board_manager  = "ooK/oSLfDJOUI" ;


/******************************************
         Table �⺻ informations            
 *****************************************/

$pern      = "10" ;	// �������� �Խù�
$namel     = "8" ;	// �̸� �ʵ��� �ִ� ����
$titll     = "40" ;	// ���� �ʵ��� �ִ� ����
$width     = "550" ;	// �Խ��� �ʺ�
$headname  = "0";   // ���Ӹ� 

/******************************************
         List page ����            
 *****************************************/

$l0_bg     = "#a5b5c5" ; // �۸�� �׵θ�
$l0_fg     = "#ffffff" ; // �۸�� �׵θ� ����
$l1_bg     = "#a5c5c5" ; // �۸�� ������ ���
$l1_fg     = "#ffffff" ; // �۸�� ������ ����
$l2_bg     = "#ffffff" ; // �۸�� ����� ���
$l2_fg     = "#555555" ; // �۸�� ����� ����
$l3_bg     = "#dcdcdc" ; // �۸�� ����� ���
$l3_fg     = "#555555" ; // �۸�� ����� ����


/******************************************
         ���� ���� page ����            
 *****************************************/

$r0_bg     = "#a5b5c5" ; // �ۺ��� �׵θ�
$r0_fg     = "#ffffff" ; // �ۺ��� �׵θ� ����
$r1_bg     = "#a5c5c5" ; // �ۺ��� ������ ���
$r1_fg     = "#ffffff" ; // �ۺ��� ������ ����
$r2_bg     = "#dcdcdc" ; // �ۺ��� ����� ���
$r2_fg     = "#555555" ; // �ۺ��� ����� ����
$r3_bg     = "#ffffff" ; // �ۺ��� �۳��� ���
$r3_fg     = "#555555" ; // �ۺ��� �۳��� ����

$t0_bg     = "#778899" ; // ���� �ö�� �� ǥ��
$mo_bg     = "#e5e5e5" ; // ���콺 Ŀ�� ������ ����
$sch_bg    = "#c5a5a5" ;  // �˻�â ���

/******************************************
         Menu ����
 *****************************************/

// menu�� �������� ��뿩��
$menuallow	= "no" ;

// menu�� �����ٽÿ� home dirctory�� link
$homelink	= "" ;

// menu�� ������ �ÿ� list page�� link
$backlink	= "javascript:history.back()" ;


/******************************************
         ��Ÿ ����            
 *****************************************/

// yes�� �����ϸ� ���ȭ�� �� �Է� � ���� �ø��� ����
$file_upload = "no" ;

// ���� ���ε� ���丮
$filesavedir = "./include/$table/files" ;

//�ִ� ���� ������. �̰� php3.ini ���� �����ؾ��� �⺻ 2M.
$maxfilesize = "2000000" ;

// �����ּҸ� ������ ���� �ö�� �� ���Ϸ� �����ش�.
$mailtoadmin = "" ;

// ������ �� �� ���� ���� �� ������� ������ ��������.
$mailtowriter = "no" ;

// �Խ����� ��ġ�� TOP ���丮
$bbshome = "" ;

// Board version (�ǵ��� ���ÿ�)
$webboard_version = "0.6.7" ;

// Ȩ������ ��� ����
$use_url = "yes" ;

// ���ڿ����ּ� ��� ����
$use_email   = "yes" ;

?>