<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DatabaseHelper
{
    /**
     * Get current database driver
     */
    public static function getDriver(): string
    {
        return config('database.connections.' . config('database.default') . '.driver');
    }

    /**
     * Check if current driver is PostgreSQL
     */
    public static function isPostgreSQL(): bool
    {
        return self::getDriver() === 'pgsql';
    }

    /**
     * Check if current driver is MySQL/MariaDB
     */
    public static function isMySQL(): bool
    {
        return in_array(self::getDriver(), ['mysql', 'mariadb']);
    }

    /**
     * Get timestamp difference in microseconds
     */
    public static function getTimestampDiff(string $column1, string $column2): string
    {
        if (self::isPostgreSQL()) {
            return "EXTRACT(EPOCH FROM ({$column1} - {$column2})) * 1000000";
        }

        return "TIMESTAMPDIFF(MICROSECOND, {$column2}, {$column1})";
    }

    /**
     * Get boolean value for current database
     */
    public static function getBooleanValue(bool $value): mixed
    {
        if (self::isPostgreSQL()) {
            return $value;
        }

        return $value ? 1 : 0;
    }

    /**
     * Get date function for current database
     */
    public static function getDateFunction(string $column): string
    {
        if (self::isPostgreSQL()) {
            return "DATE({$column})";
        }

        return "DATE({$column})"; // Same for MySQL
    }

    /**
     * Execute database-specific query
     */
    public static function executeQuery(string $postgresQuery, string $mysqlQuery)
    {
        if (self::isPostgreSQL()) {
            return DB::select($postgresQuery);
        }

        return DB::select($mysqlQuery);
    }
}
