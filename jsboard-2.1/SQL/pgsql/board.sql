--
-- sql ����
-- ù������ -- �� �����ϸ� �ش� ������ �ּ����� ó��
-- sql ���� �̾����� ������ tab ���� �����ؾ� ��.
-- ���� ������ ���õ�
--

CREATE TABLE @table@ (
		no serial NOT NULL,
		num int DEFAULT '0' NOT NULL,
		idx int DEFAULT '0' NOT NULL,
		date int DEFAULT '0' NOT NULL,
		host text,
		name text,
		rname text,
		passwd varchar(56),
		email text,
		url text,
		title text,
		text text,
		refer int DEFAULT '0' NOT NULL,
		reyn int DEFAULT '0' NOT NULL,
		reno int DEFAULT '0' NOT NULL,
		rede int DEFAULT '0' NOT NULL,
		reto int DEFAULT '0' NOT NULL,
		html int DEFAULT '1' NOT NULL,
		comm int DEFAULT '0' NOT NULL,
		bofile varchar(100),
		bcfile varchar(100),
		bfsize int,
		PRIMARY KEY (no)
	) without oids;
CREATE INDEX @table@_num_i on @table@ (num);
CREATE INDEX @table@_idx_i on @table@ (idx);
CREATE INDEX @table@_reno_i on @table@ (reno);
CREATE INDEX @table@_date_i on @table@ (date);
CREATE INDEX @table@_reto_i on @table@ (reto);
CREATE INDEX @table@_comm_i on @table@ (comm);
