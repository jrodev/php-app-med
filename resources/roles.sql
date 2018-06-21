CREATE TABLE roles (
  idrol INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  fecreg DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (idrol)
);

CREATE TABLE permissions (
  idperm INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `desc` VARCHAR(50) NOT NULL,
  fecreg DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (idperm)
);

CREATE TABLE roleperm (
  idrol INTEGER UNSIGNED NOT NULL,
  idperm INTEGER UNSIGNED NOT NULL,
  fecreg DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idrol) REFERENCES roles(idrol),
  FOREIGN KEY (idperm) REFERENCES permissions(idperm)
);

CREATE TABLE `user` (
  iduser INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(50) NOT NULL,
  fecreg DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (iduser)
);

CREATE TABLE userrole (
  iduser INTEGER UNSIGNED NOT NULL,
  idrol INTEGER UNSIGNED NOT NULL,
  fecreg DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (iduser) REFERENCES `user`(iduser),
  FOREIGN KEY (idrol) REFERENCES roles(idrol)
);

