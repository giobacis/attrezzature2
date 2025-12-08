-- Patch movimenti magazzino: email come varchar e stato prenotazione
ALTER TABLE tbl_movmg MODIFY COLUMN email varchar(255) NOT NULL;
ALTER TABLE tbl_movmg ADD COLUMN stato varchar(20) NOT NULL DEFAULT 'in_attesa' AFTER controllato;
