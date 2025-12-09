<?php

use Glpi\Toolbox\PluginMigration;

/**
 * Install hook
 *
 * @return boolean
 */
function plugin_centrodecusto_install()
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
      
      return true;
   } catch (Exception $e) {
      error_log("Centrodecusto install error: " . $e->getMessage());
      return false;
   }
}

/**
 * Uninstall hook
 *
 * @return boolean
 */
function plugin_centrodecusto_uninstall()
{
   // Ensure the class is loaded by the autoloader before we include a file that extends it.
   if (!class_exists(PluginMigration::class)) {
      return false;
   }
   include_once __DIR__ . '/inc/migration.class.php';
   $migration = new PluginCentrodecustoMigration();
   $migration->uninstall();
   return true;
 }

/**
  * Display information on login page
  *
  * @return void
  */
  function plugin_centrodecusto_display_login () {
   echo "That line will appear on the login page!";
}

//Integra o plugin a lista suspensa do GLPI
function plugin_centrodecusto_getDropdown() {
   return [
      'PluginCentrodecustoForm' => _n('Centro de custo', 'Centros de custo', 2, 'centrodecusto'),
   ];
}
