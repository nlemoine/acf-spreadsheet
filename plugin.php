<?php

/**
 * Plugin Name: Advanced Custom Fields: ACF Spreadsheet
 * Plugin URI:  https://github.com/nlemoine/acf-color-palette
 * Description: ACF Spreadsheet
 * Version:     0.1.0
 * Author:      Nicolas Lemoine
 * Author URI:  https://github.com/nlemoine
 */

add_filter(
    'after_setup_theme', new class
    {
        /**
         * Invoke the plugin.
         *
         * @return void
         */
        public function __invoke()
        {
            if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
                include_once $composer;
            }

            $this->register();

            if (defined('ACP_FILE')) {
                $this->hookAdminColumns();
            }
        }

        /**
         * Register the field type with ACF.
         *
         * @return void
         */
        protected function register()
        {
            foreach (['acf/include_field_types', 'acf/register_fields'] as $hook) {
                add_filter(
                    $hook, function () {
                        return new HelloNico\AcfSpreadsheet\SpreadsheetField(
                            untrailingslashit(plugin_dir_url(__FILE__)),
                            untrailingslashit(plugin_dir_path(__FILE__))
                        );
                    }
                );
            }
        }

        /**
         * Hook the Admin Columns Pro plugin to provide basic field support
         * if detected on the current WordPress installation.
         *
         * @return void
         */
        protected function hookAdminColumns()
        {
            add_filter(
                'ac/column/value', function ($value, $id, $column) {
                    if (! is_a($column, '\ACA\ACF\Column')
                        || $column->get_acf_field_option('type') !== 'color_palette'
                    ) {
                        return $value;
                    }

                    return get_field($column->get_meta_key()) ?? $value;
                }, 10, 3
            );
        }
    }
);
