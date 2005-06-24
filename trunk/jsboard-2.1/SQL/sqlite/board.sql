CREATE TABLE @table@ (
		no integer NOT NULL PRIMARY KEY,
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
		bfsize int(4)
	);
CREATE INDEX @table@_num_i on @table@(num);
CREATE INDEX @table@_idx_i on @table@(idx);
CREATE INDEX @table@_date_i on @table@(date);
CREATE INDEX @table@_reno_i on @table@(reno);
CREATE INDEX @table@_reto_i on @table@(reto);
CREATE INDEX @table@_comm_i on @table@(comm);
