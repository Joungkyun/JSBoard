# sql 정의
# # 뒤의 문자는 주석으로 처리
# sql 문의 이어지는 라인은 tab 으로 시작해야 함.
# 공백 라인은 무시됨

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

