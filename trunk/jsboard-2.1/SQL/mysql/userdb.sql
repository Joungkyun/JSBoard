# sql ����
# # ���� ���ڴ� �ּ����� ó��
# sql ���� �̾����� ������ tab ���� �����ؾ� ��.
# ���� ������ ���õ�

CREATE TABLE userdb (
		no int(6) NOT NULL auto_increment,
		nid varchar(30) NOT NULL default '',
		name varchar(30) NOT NULL default '',
		email tinytext NOT NULL,
		url tinytext NOT NULL,
		passwd varchar(100) NOT NULL default '',
		position int(1) NOT NULL default '0',
		PRIMARY KEY  (no),
		KEY no (no),
		UNIQUE KEY nid (nid),
		KEY name (name),
		KEY position (position)
	) TYPE=MyISAM;

