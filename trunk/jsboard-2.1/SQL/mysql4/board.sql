# sql ����
# # ���� ���ڴ� �ּ����� ó��
# sql ���� �̾����� ������ tab ���� �����ؾ� ��.
# ���� ������ ���õ�

CREATE TABLE @table@ (
		no int(6) NOT NULL auto_increment,
		num int(6) DEFAULT '0' NOT NULL,
		idx int(6) DEFAULT '0' NOT NULL,
		date int(11) DEFAULT '0' NOT NULL,
		host tinytext,
		name tinytext,
		rname tinytext,
		passwd varchar(56),
		email tinytext,
		url tinytext,
		title tinytext,
		text mediumtext,
		refer int(6) DEFAULT '0' NOT NULL,
		reyn int(1) DEFAULT '0' NOT NULL,
		reno int(6) DEFAULT '0' NOT NULL,
		rede int(6) DEFAULT '0' NOT NULL,
		reto int(6) DEFAULT '0' NOT NULL,
		html int(1) DEFAULT '1' NOT NULL,
		comm int(6) DEFAULT '0' NOT NULL,
		bofile varchar(100),
		bcfile varchar(100),
		bfsize int(4),
		KEY no (no),
		KEY num (num),
		KEY idx (idx),
		KEY reno (reno),
		KEY date (date),
		KEY reto (reto),
		KEY comm (comm),
		PRIMARY KEY (no)
	)
