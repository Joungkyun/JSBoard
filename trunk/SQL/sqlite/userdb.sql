CREATE TABLE userdb (
		no integer NOT NULL PRIMARY KEY,
		nid varchar(30) UNIQUE NOT NULL,
		name varchar(30) NOT NULL,
		email tinytext NOT NULL,
		url tinytext NOT NULL,
		passwd varchar(100) NOT NULL default '',
		position int(1) NOT NULL default '0'
	);
CREATE INDEX userdb_nid_i on userdb(nid);
CREATE INDEX userdb_name_i on userdb(name);
CREATE INDEX userdb_pos_i on userdb(position);

