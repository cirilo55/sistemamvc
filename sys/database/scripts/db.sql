create database sistemamvc

create table users (
	idUsuario int PRIMARY KEY AUTO_INCREMENT,
  nomeUsuario varchar(30),
  lastName varchar(30),
  password varchar(255),
  userType int
)


/*usertype = 1 (Admin) usetype = 2 (Padrão)*/

INSERT INTO users (idUsuario,nomeUsuario,lastName,userType,password) VALUES (1,'CIRILO','1234',0, '1')

CREATE TABLE `query_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `query_date` datetime NOT NULL,
  `query_sql` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;