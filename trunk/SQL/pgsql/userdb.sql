--
-- sql ����
-- ù������ -- �� �����ϸ� �ش� ������ �ּ����� ó��
-- sql ���� �̾����� ������ tab ���� �����ؾ� ��.
-- ���� ������ ���õ�
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

