# sql ����
# # ���� ���ڴ� �ּ����� ó��
# sql ���� �̾����� ������ tab ���� �����ؾ� ��.
# ���� ������ ���õ�

CREATE TABLE @table@_comm (
		no int(6) NOT NULL auto_increment,
		reno int(20) NOT NULL default '0',
		rname tinytext,
		name tinytext,
		passwd varchar(56) default NULL,
		text mediumtext,
		host tinytext,
		date int(11) NOT NULL default '0',
		PRIMARY KEY  (no),
		KEY parent (reno)
	) TYPE=MyISAM;
