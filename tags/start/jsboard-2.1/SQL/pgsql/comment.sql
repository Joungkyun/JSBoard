--
-- sql 정의
-- 첫라인이 -- 로 시작하면 해당 라인을 주석으로 처리
-- sql 문의 이어지는 라인은 tab 으로 시작해야 함.
-- 공백 라인은 무시됨
--

CREATE TABLE @table@_comm (
		no serial NOT NULL,
		reno int NOT NULL default '0',
		rname text,
		name text,
		passwd varchar(56) default NULL,
		text text,
		host text,
		date int NOT NULL default '0',
		PRIMARY KEY  (no)
	) without oids;
CREATE INDEX @table@_comm_reno_i on @table@_comm (reno);
