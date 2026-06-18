<?php

use Sys\Database\Migrations\MigrationInterface;
use Sys\Orm\ConnectionInterface;

return new class implements MigrationInterface {
    public function name(): string
    {
        return '2026_06_18_000001_create_base_tables';
    }

    public function up(ConnectionInterface $connection): void
    {
        $connection->prepareAndExecute(
            'CREATE TABLE IF NOT EXISTS users (
                id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
                userName VARCHAR(30) NOT NULL,
                lastName VARCHAR(30),
                userEmail VARCHAR(80),
                password VARCHAR(255) NOT NULL,
                userType INT NOT NULL DEFAULT 0,
                imagePath VARCHAR(255),
                createdAt DATETIME,
                updatedAt DATETIME
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );

        $connection->prepareAndExecute(
            'CREATE TABLE IF NOT EXISTS notifications (
                id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
                title VARCHAR(16),
                description VARCHAR(255),
                user_notification CHAR(36) NOT NULL,
                INDEX idx_notifications_user_notification (user_notification),
                CONSTRAINT fk_notifications_user
                    FOREIGN KEY (user_notification) REFERENCES users(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );

        $connection->prepareAndExecute(
            'CREATE TABLE IF NOT EXISTS mainConfig (
                id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
                mainColor VARCHAR(10),
                mainPage VARCHAR(30),
                createdAt DATETIME,
                updatedAt DATETIME
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );

        $connection->prepareAndExecute(
            'CREATE TABLE IF NOT EXISTS systemModule (
                id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
                moduleName VARCHAR(30),
                `order` INT NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );

        $connection->prepareAndExecute(
            'CREATE TABLE IF NOT EXISTS moduleItem (
                id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
                itemName VARCHAR(30),
                idModulo CHAR(36) NOT NULL,
                archorValue VARCHAR(30),
                INDEX idx_module_item_id_modulo (idModulo),
                CONSTRAINT fk_module
                    FOREIGN KEY (idModulo) REFERENCES systemModule(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );

        $connection->prepareAndExecute(
            'CREATE TABLE IF NOT EXISTS query_history (
                id CHAR(36) NOT NULL DEFAULT (UUID()),
                query_date DATETIME NOT NULL,
                query_sql TEXT NOT NULL,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );

        $connection->prepareAndExecute(
            'CREATE TABLE IF NOT EXISTS clients (
                id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
                clientName VARCHAR(30),
                createdAt DATETIME,
                updatedAt DATETIME
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );

        $connection->prepareAndExecute(
            'CREATE TABLE IF NOT EXISTS task (
                id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
                taskName VARCHAR(30),
                description VARCHAR(255),
                limitDate DATETIME,
                status INT,
                user_task_responsible CHAR(36),
                user_task_owner CHAR(36),
                createdAt DATETIME,
                updatedAt DATETIME,
                INDEX idx_task_user_task_responsible (user_task_responsible),
                INDEX idx_task_user_task_owner (user_task_owner),
                CONSTRAINT fk_task_responsible
                    FOREIGN KEY (user_task_responsible) REFERENCES users(id)
                    ON DELETE SET NULL,
                CONSTRAINT fk_task_owner
                    FOREIGN KEY (user_task_owner) REFERENCES users(id)
                    ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
    }

    public function down(ConnectionInterface $connection): void
    {
        foreach (['task', 'moduleItem', 'notifications', 'clients', 'query_history', 'systemModule', 'mainConfig', 'users'] as $table) {
            $connection->prepareAndExecute('DROP TABLE IF EXISTS `' . $table . '`');
        }
    }
};
