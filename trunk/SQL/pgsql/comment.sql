--
-- sql ����
-- ù������ -- �� �����ϸ� �ش� ������ �ּ����� ó��
-- sql ���� �̾����� ������ tab ���� �����ؾ� ��.
-- ���� ������ ���õ�
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
