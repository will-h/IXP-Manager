CREATE TABLE patch_panel (id INT AUTO_INCREMENT NOT NULL, cabinet_id INT NOT NULL, name VARCHAR(255) NOT NULL, medium VARCHAR(255) NOT NULL, connector VARCHAR(255) NOT NULL, duplex_allowed TINYINT(1) NOT NULL, notes LONGTEXT DEFAULT NULL, u_position INT NOT NULL, installed DATE NOT NULL, INDEX IDX_79A52562D351EC (cabinet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE patch_panel_port (id INT AUTO_INCREMENT NOT NULL, port_object_id INT NOT NULL, patch_panel_id INT NOT NULL, position INT NOT NULL, medium VARCHAR(255) NOT NULL, connector VARCHAR(255) NOT NULL, available_for_use TINYINT(1) NOT NULL, duplex INT NOT NULL, colo_reference VARCHAR(255) DEFAULT NULL, ordered DATE DEFAULT NULL, completed DATE DEFAULT NULL, ceased DATE DEFAULT NULL, notes LONGTEXT DEFAULT NULL, deleted TINYINT(1) NOT NULL, deleted_on DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4BE40BC219E664B3 (port_object_id), INDEX IDX_4BE40BC2635D5D87 (patch_panel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE patch_panel_port_object (id INT AUTO_INCREMENT NOT NULL, console_server_connection_id INT NOT NULL, customer_equipment_id INT NOT NULL, patch_panel_port_id INT NOT NULL, switch_port_id INT NOT NULL, UNIQUE INDEX UNIQ_F19A8C2DF2474E76 (console_server_connection_id), UNIQUE INDEX UNIQ_F19A8C2D5FA72AE7 (customer_equipment_id), UNIQUE INDEX UNIQ_F19A8C2DB0F978FF (patch_panel_port_id), UNIQUE INDEX UNIQ_F19A8C2DC1DA6A2A (switch_port_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE patch_panel ADD CONSTRAINT FK_79A52562D351EC FOREIGN KEY (cabinet_id) REFERENCES cabinet (id);
ALTER TABLE patch_panel_port ADD CONSTRAINT FK_4BE40BC219E664B3 FOREIGN KEY (port_object_id) REFERENCES patch_panel_port_object (id);
ALTER TABLE patch_panel_port ADD CONSTRAINT FK_4BE40BC2635D5D87 FOREIGN KEY (patch_panel_id) REFERENCES patch_panel (id);
ALTER TABLE patch_panel_port_object ADD CONSTRAINT FK_F19A8C2DF2474E76 FOREIGN KEY (console_server_connection_id) REFERENCES consoleserverconnection (id);
ALTER TABLE patch_panel_port_object ADD CONSTRAINT FK_F19A8C2D5FA72AE7 FOREIGN KEY (customer_equipment_id) REFERENCES custkit (id);
ALTER TABLE patch_panel_port_object ADD CONSTRAINT FK_F19A8C2DB0F978FF FOREIGN KEY (patch_panel_port_id) REFERENCES patch_panel_port (id);
ALTER TABLE patch_panel_port_object ADD CONSTRAINT FK_F19A8C2DC1DA6A2A FOREIGN KEY (switch_port_id) REFERENCES switchport (id);


ALTER TABLE patch_panel_port DROP FOREIGN KEY FK_4BE40BC219E664B3;
DROP INDEX UNIQ_4BE40BC219E664B3 ON patch_panel_port;
ALTER TABLE patch_panel_port DROP port_object_id;
ALTER TABLE patch_panel_port_object DROP INDEX UNIQ_F19A8C2DF2474E76, ADD INDEX IDX_F19A8C2DF2474E76 (console_server_connection_id);
ALTER TABLE patch_panel_port_object DROP INDEX UNIQ_F19A8C2D5FA72AE7, ADD INDEX IDX_F19A8C2D5FA72AE7 (customer_equipment_id);
ALTER TABLE patch_panel_port_object DROP INDEX UNIQ_F19A8C2DB0F978FF, ADD INDEX IDX_F19A8C2DB0F978FF (patch_panel_port_id);
ALTER TABLE patch_panel_port_object DROP INDEX UNIQ_F19A8C2DC1DA6A2A, ADD INDEX IDX_F19A8C2DC1DA6A2A (switch_port_id);
ALTER TABLE patch_panel_port_object ADD port_id INT NOT NULL;
ALTER TABLE patch_panel_port_object ADD CONSTRAINT FK_F19A8C2D76E92A9C FOREIGN KEY (port_id) REFERENCES patch_panel_port (id);
CREATE UNIQUE INDEX UNIQ_F19A8C2D76E92A9C ON patch_panel_port_object (port_id)

ALTER TABLE patch_panel_port ADD assigned DATE DEFAULT NULL, ADD connected DATE DEFAULT NULL, ADD cancelled DATE DEFAULT NULL, DROP ordered, DROP completed, DROP ceased
