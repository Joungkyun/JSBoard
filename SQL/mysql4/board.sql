# sql 정의
# # 뒤의 문자는 주석으로 처리
# sql 문의 이어지는 라인은 tab 으로 시작해야 함.
# 공백 라인은 무시됨

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
