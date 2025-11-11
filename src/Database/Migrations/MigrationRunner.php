<?php
namespace ZorgFinder\Reviews\Database\Migrations;

class MigrationRunner
{
    public function run(): void
    {
        $migrations = [
            new CreateReviewsTable(),
        ];

        foreach ($migrations as $migration) {
            if (method_exists($migration, 'up')) {
                try {
                    $migration->up();
                } catch (\Throwable $e) {
                    error_log('[ZorgFinder Reviews Migration Error] ' . $e->getMessage());
                }
            }
        }
    }
}
