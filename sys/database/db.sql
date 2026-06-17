CREATE DATABASE IF NOT EXISTS sistemamvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistemamvc;

CREATE TABLE IF NOT EXISTS users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  userName VARCHAR(30) NOT NULL,
  lastName VARCHAR(30),
  userEmail VARCHAR(80),
  password VARCHAR(255) NOT NULL,
  userType INT NOT NULL DEFAULT 0,
  imagePath VARCHAR(255),
  createdAt DATETIME,
  updatedAt DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notifications (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(16),
  description VARCHAR(255),
  user_notification INT NOT NULL,
  CONSTRAINT fk_notifications_user
    FOREIGN KEY (user_notification) REFERENCES users(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS mainConfig (
  id INT PRIMARY KEY AUTO_INCREMENT,
  mainColor VARCHAR(10),
  mainPage VARCHAR(30),
  createdAt DATETIME,
  updatedAt DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS systemModule (
  id INT PRIMARY KEY AUTO_INCREMENT,
  moduleName VARCHAR(30),
  `order` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS moduleItem (
  id INT PRIMARY KEY AUTO_INCREMENT,
  itemName VARCHAR(30),
  idModulo INT NOT NULL,
  archorValue VARCHAR(30),
  CONSTRAINT fk_module
    FOREIGN KEY (idModulo) REFERENCES systemModule(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS query_history (
  id INT NOT NULL AUTO_INCREMENT,
  query_date DATETIME NOT NULL,
  query_sql TEXT NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS clients (
  id INT PRIMARY KEY AUTO_INCREMENT,
  clientName VARCHAR(30),
  createdAt DATETIME,
  updatedAt DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS task (
  id INT PRIMARY KEY AUTO_INCREMENT,
  taskName VARCHAR(30),
  description VARCHAR(255),
  limitDate DATETIME,
  status INT,
  user_task_responsible INT,
  user_task_owner INT,
  createdAt DATETIME,
  updatedAt DATETIME,
  CONSTRAINT fk_task_responsible
    FOREIGN KEY (user_task_responsible) REFERENCES users(id)
    ON DELETE SET NULL,
  CONSTRAINT fk_task_owner
    FOREIGN KEY (user_task_owner) REFERENCES users(id)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* userType = 0 (Admin), 1 (Usuario), 2 (Visitante) */
INSERT INTO users (id, userName, lastName, userType, password, createdAt, updatedAt) VALUES
  (1, 'Admin', 'Sistema', 0, '$2y$10$BWisgENvxN0w7ruaHLS.ruRH84f7slYOADvWWVEHRq1kuc4cQ13NK', NOW(), NOW()),
  (2, 'CIRILO', '1234', 0, '$2y$10$BWisgENvxN0w7ruaHLS.ruRH84f7slYOADvWWVEHRq1kuc4cQ13NK', NOW(), NOW()),
  (3, 'Cid', '1234', 0, '$2y$10$BWisgENvxN0w7ruaHLS.ruRH84f7slYOADvWWVEHRq1kuc4cQ13NK', NOW(), NOW())
ON DUPLICATE KEY UPDATE
  userName = VALUES(userName),
  lastName = VALUES(lastName),
  userType = VALUES(userType),
  password = VALUES(password),
  updatedAt = NOW();

INSERT INTO systemModule (id, moduleName, `order`) VALUES
  (1, 'Operacional', 1),
  (2, 'Configuracoes', 9),
  (3, 'Dashboards', 2)
ON DUPLICATE KEY UPDATE
  moduleName = VALUES(moduleName),
  `order` = VALUES(`order`);

INSERT INTO moduleItem (id, itemName, idModulo, archorValue) VALUES
  (1, 'Usuarios', 2, 'users'),
  (2, 'Tarefas', 1, 'tarefas'),
  (3, 'Clientes', 1, 'clientes'),
  (4, 'Configuracoes Gerais', 2, 'config')
ON DUPLICATE KEY UPDATE
  itemName = VALUES(itemName),
  idModulo = VALUES(idModulo),
  archorValue = VALUES(archorValue);

INSERT INTO clients (id, clientName, createdAt, updatedAt) VALUES
  (1, 'empresa n1', NOW(), NOW()),
  (2, 'empresa n2', NOW(), NOW())
ON DUPLICATE KEY UPDATE
  clientName = VALUES(clientName),
  updatedAt = NOW();

INSERT INTO mainConfig (id, createdAt, updatedAt) VALUES (1, NOW(), NOW())
ON DUPLICATE KEY UPDATE updatedAt = NOW();
