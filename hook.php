<?php

/**
 * Install hook
 *
 * @return boolean
 */
function plugin_centrodecusto_install()
{
   include_once __DIR__ . '/inc/migration.class.php';
   \Glpi\Toolbox\PluginMigration::makeMigration('centrodecusto', PluginCentrodecustoMigration::class);
   return true;
}

/**
 * Uninstall hook
 *
 * @return boolean
 */
function plugin_centrodecusto_uninstall()
{
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
