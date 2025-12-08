<?php

if (!defined('GLPI_ROOT')) {
    die('Sorry. You can\'t access this file directly');
 }

class PluginCentrodecustoMigration extends \Glpi\Toolbox\PluginMigration
{
    public function __construct($do_db_checks = true)
    {
        // Overload the constructor to prevent DB access during uninstall
        if ($do_db_checks) {
            parent::__construct();
        }
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

        $this->db->createTable(
            $table_name,
            "
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `entities_id` TINYINT(1) NOT NULL,
            `is_recursive` TINYINT(1) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `completename` VARCHAR(255) NOT NULL,
            `ccusto` INT(11) NOT NULL,
            `visivel_chamado` TINYINT(1) NOT NULL,
            `visivel_projeto` TINYINT(1) NOT NULL,
            `itens` TINYINT(1) NOT NULL,
            `user` TINYINT(1) NOT NULL,
            `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `date_mod` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `comment` TEXT,
            PRIMARY KEY (`id`),
            KEY `name` (`name`)
            ",
            [
                'engine'  => 'InnoDB',
                'charset' => 'utf8mb4',
                'collate' => 'utf8mb4_unicode_ci',
            ]
        );
    }

    private function createCcustoUsersTable(): void
    {
        $table_name = 'glpi_plugin_centrodecusto_ccusto_users';

        if ($this->db->tableExists($table_name)) {
            return;
        }

        $this->db->createTable(
            $table_name,
            "
            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `users_id` INT(10) UNSIGNED NOT NULL,
            `ccusto_id` INT(10) UNSIGNED NOT NULL,
            `is_director` TINYINT(4) NOT NULL,
            `is_manager` TINYINT(4) NOT NULL,
            `is_belongs` TINYINT(4) NOT NULL,
            PRIMARY KEY (`id`)
            ",
            [
                'engine'  => 'InnoDB',
                'charset' => 'utf8mb4',
                'collate' => 'utf8mb4_unicode_ci',
            ]
        );
    }

    public function uninstall(): void
    {
        $tables = [
            'glpi_plugin_centrodecusto_ccusto_users',
            'glpi_plugin_centrodecusto_ccusto',
        ];

        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->db->dropTable($table);
            }
        }
    }
}