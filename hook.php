<?php
declare(strict_types=1);
if (!defined('GLPI_ROOT')) { define('GLPI_ROOT', realpath(__DIR__ . '/../..')); }

use Glpi\Toolbox\PluginMigration;

/**
 * Install hook
 */
function plugin_centrodecusto_install(): bool
{
   try {
      include_once __DIR__ . '/inc/migration.class.php';
      $migration = new PluginCentrodecustoMigration(true);
      // Execute migration steps
      $steps = PluginCentrodecustoMigration::getMigrationSteps();
      foreach ($steps as $version => $method) {
         if (method_exists($migration, $method)) {
            $migration->$method();
         }
      }
      if (class_exists('Toolbox')) {
         Toolbox::logInFile('centrodecusto', sprintf(
            'INFO [%s:%s] Plugin installed successfully by user=%s',
            __FILE__, __FUNCTION__, $_SESSION['glpiname'] ?? 'unknown'
         ));
      }
      return true;
   } catch (\Exception $e) {
      if (class_exists('Toolbox')) {
         Toolbox::logInFile('centrodecusto', sprintf(
            'ERROR [%s:%s] Install error: %s, user=%s',
            __FILE__, __FUNCTION__, $e->getMessage(), $_SESSION['glpiname'] ?? 'unknown'
         ));
      }
      error_log("Centrodecusto install error: " . $e->getMessage());
      return false;
   }
}
// ...existing code...


/**
 * Uninstall hook
 *
 * @return bool
 */
function plugin_centrodecusto_uninstall(): bool
{
   // Ensure the class is loaded by the autoloader before we include a file that extends it.
   if (!class_exists(PluginMigration::class)) {
      if (class_exists('Toolbox')) {
         Toolbox::logInFile('centrodecusto', sprintf(
            'ERROR [%s:%s] PluginMigration class missing during uninstall, user=%s',
            __FILE__, __FUNCTION__, $_SESSION['glpiname'] ?? 'unknown'
         ));
      }
      return false;
   }
   include_once __DIR__ . '/inc/migration.class.php';
   $migration = new PluginCentrodecustoMigration();
   $migration->uninstall();
   if (class_exists('Toolbox')) {
      Toolbox::logInFile('centrodecusto', sprintf(
         'INFO [%s:%s] Plugin uninstalled successfully by user=%s',
         __FILE__, __FUNCTION__, $_SESSION['glpiname'] ?? 'unknown'
      ));
   }
   return true;
}


/**
 * Display information on login page
 *
 * @return void
 */
function plugin_centrodecusto_display_login(): void
{
   echo "That line will appear on the login page!";
}

/**
 * Integra o plugin a lista suspensa do GLPI
 *
 * @return array
 */
function plugin_centrodecusto_getDropdown(): array
{
   return [
      'PluginCentrodecustoForm' => _n('Centro de custo', 'Centros de custo', 2, 'centrodecusto'),
   ];
}
