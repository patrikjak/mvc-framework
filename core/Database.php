<?php


namespace app\core;


use PDO;

class Database
{
    public PDO $pdo;

    /**
     * Database constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $dsn = $config['dsn'];
        $user = $config['user'];
        $password = $config['password'];
        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Apply migration if there are any, if not log message with info
     */
    public function applyMigration()
    {
        $this->createMigrationsTable();
        $applied_migrations = $this->get_applied_migrations();

        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $to_apply_migrations = array_diff($files, $applied_migrations);

        $new_migrations = [];

        foreach ($to_apply_migrations as $migration) {
            // skip unwanted folders
            if ($migration === '.' || $migration === '..') {
                continue;
            }
            require_once Application::$ROOT_DIR.'/migrations/'.$migration;

            $class_name = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $class_name();
            $instance->up();

            $new_migrations[] = $migration;
        }

        if (!empty($new_migrations)) {
            $this->saveMigration($new_migrations);
        }
        else {
            echo $this->log("All migrations are applied");
        }
    }

    /**
     * Create migration table
     *
     */
    public function createMigrationsTable()
    {
        $this->pdo->exec(
          "CREATE TABLE IF NOT EXISTS migrations (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL ,
                migration VARCHAR(255) NOT NULL ,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ,
                CONSTRAINT migrations_pk PRIMARY KEY (id) 
            ) ENGINE=InnoDB;"
        );
    }

    /**
     * Check for existing migrations
     *
     * @return array
     */
    public function get_applied_migrations(): array
    {
        $stmt = $this->pdo->prepare("SELECT migration FROM migrations");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    /**
     * Insert into migrations table new migrations
     *
     * @param array $migrations
     */
    public function saveMigration(array $migrations)
    {
        $values = implode(',', array_map(fn($m) => "('$m')", $migrations));

        $stmt = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $values");
        $stmt->execute();
    }

    /**
     * Log message
     *
     * @param $message
     * @return string
     */
    protected function log($message): string
    {
        date_default_timezone_set('Europe/Bratislava');
        return '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
    }
}