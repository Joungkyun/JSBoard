--
-- sql 정의
-- 첫라인이 -- 로 시작하면 해당 라인을 주석으로 처리
-- sql 문의 이어지는 라인은 tab 으로 시작해야 함.
-- 공백 라인은 무시됨
--

CREATE TABLE userdb (
		no serial NOT NULL,
		nid varchar(30) NOT NULL default '',
		name varchar(30) NOT NULL default '',
		email text NOT NULL,
		url text NOT NULL,
		passwd varchar(100) NOT NULL default '',
		position int NOT NULL default '0',
		PRIMARY KEY  (no)
	) without oids;
CREATE UNIQUE INDEX userdb_nid_i on userdb (nid);
CREATE INDEX userdb_name_i on userdb (name);
CREATE INDEX userdb_position_i on userdb (position);

