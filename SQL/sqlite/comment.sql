CREATE TABLE @table@_comm (
		no integer NOT NULL PRIMARY KEY,
		reno int(20) NOT NULL default '0',
		rname tinytext,
		name tinytext,
		passwd varchar(56) default NULL,
		text mediumtext,
		host tinytext,
		date int(11) NOT NULL default '0'
	);
CREATE INDEX @table@_comm_reno_i on @table@_comm(reno);
