<?php


use app\core\Application;

class m_0001_initial
{
    public function up()
    {
        $db = Application::$app->db;
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
              id INT UNSIGNED AUTO_INCREMENT NOT NULL ,
              email VARCHAR(255) NOT NULL ,
              name VARCHAR(50) NOT NULL ,
              status TINYINT NOT NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ,
              CONSTRAINT users_pk PRIMARY KEY (id)
            ) ENGINE=InnoDB;
        ";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = Application::$app->db;
        $sql = "DROP TABLE users;";
        $db->pdo->exec($sql);
    }
}