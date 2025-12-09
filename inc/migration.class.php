<?php

if (!defined('GLPI_ROOT')) {
    die('Sorry. You can\'t access this file directly');
 }

class PluginCentrodecustoMigration
{
    private $db;

    public function __construct($do_db_checks = true)
    {
        global $DB;
        $this->db = $DB;
    }

    public static function getMigrationSteps(): array
    {
        return [
            '1.0.0' => 'migrationTo100',
        ];
    }

    public function migrationTo100(): void
    {
        $this->createCcustoTable();
        $this->createCcustoUsersTable();
    }

    private function createCcustoTable(): void
    {
        $table_name = 'glpi_plugin_centrodecusto_ccusto';

        if ($this->db->tableExists($table_name)) {
            return;
        }

        $query = "CREATE TABLE `" . $table_name . "` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `entities_id` TINYINT(1) NOT NULL,
            `is_recursive` TINYINT(1) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `completename` VARCHAR(255) NOT NULL,
            `ccusto` BIGINT UNSIGNED NOT NULL,
            `visivel_chamado` TINYINT(1) NOT NULL,
            `visivel_projeto` TINYINT(1) NOT NULL,
            `itens` TINYINT(1) NOT NULL,
            `user` TINYINT(1) NOT NULL,
            `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `date_mod` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `comment` TEXT,
            PRIMARY KEY (`id`),
            KEY `name` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->doQuery($query);
    }

    private function createCcustoUsersTable(): void
    {
        $table_name = 'glpi_plugin_centrodecusto_ccusto_users';

        if ($this->db->tableExists($table_name)) {
            return;
        }

        $query = "CREATE TABLE `" . $table_name . "` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `users_id` BIGINT UNSIGNED NOT NULL,
            `ccusto_id` BIGINT UNSIGNED NOT NULL,
            `is_director` TINYINT(4) NOT NULL,
            `is_manager` TINYINT(4) NOT NULL,
            `is_belongs` TINYINT(4) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `users_id` (`users_id`),
            KEY `ccusto_id` (`ccusto_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->doQuery($query);
    }

    public function uninstall(): void
    {
        $tables = [
            'glpi_plugin_centrodecusto_ccusto_users',
            'glpi_plugin_centrodecusto_ccusto',
        ];

        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->db->doQuery("DROP TABLE IF EXISTS `" . $table . "`");
            }
        }
    }
}