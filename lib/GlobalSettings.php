<?php

namespace FriendsOfRedaxo\GlobalSettings;


class GlobalSettings
{
    public const FIELD_PREFIX = 'glob_';
    public const CACHE_FILENAME = 'cache.php';
    protected static array $globalValues = [];
    protected static ?int $curClangId = null;
    protected static ?int $defaultClang = null;
    protected static ?string $cacheFile = null;

    public static function init(): void
    {
        self::$curClangId = \rex_clang::getCurrentId();
        self::$defaultClang = \rex_clang::getStartId();
        self::$cacheFile = \rex_path::addonCache('global_settings', self::CACHE_FILENAME);

        $cache = \rex_file::getCache(self::$cacheFile);
        
        if (is_array($cache)) {
            self::$globalValues = $cache;
        } else {
            // retrieve from db
            $sql = \rex_sql::factory();
            $result = $sql->getArray('SELECT * FROM ' . \rex::getTablePrefix() . 'global_settings');

            if (is_array($result)) {
                // build globalValues array based on domain and clang then key/value
                foreach ($result as $row) {
                    $domainId = isset($row['domain_id']) ? (int) $row['domain_id'] : 1;
                    $clangId = (int) $row['clang'];
                    unset($row['clang']);
                    unset($row['domain_id']);
                    self::$globalValues[$domainId][$clangId] = $row;
                }

                // store in cachefile for next time
                \rex_file::putCache(self::$cacheFile, self::$globalValues);
            }
        }
    }

    public static function deleteCache(): bool
    {
        if (self::$cacheFile && file_exists(self::$cacheFile)) {
            return \rex_file::delete(self::$cacheFile);
        }
        // Fallback if init was not called
        return \rex_file::delete(\rex_path::addonCache('global_settings', self::CACHE_FILENAME));
    }

    protected static function getCurrentDomainId(): int
    {
        if (\rex_addon::get('yrewrite')->isAvailable()) {
            $domain = \rex_yrewrite::getCurrentDomain();
            if ($domain) {
                return (int) $domain->getId();
            }
            return (int) \rex_yrewrite::getDefaultDomain()->getId();
        }
        return 1;
    }

    public static function getDefaultValue(string $field, bool $allowEmpty = true, ?int $domainId = null): mixed
    {
        return self::getValue($field, self::$defaultClang, $allowEmpty, $domainId);
    }

    public static function getValue(string $field, ?int $clangId = null, bool $allowEmpty = true, ?int $domainId = null): mixed
    {
        if (null === $clangId) {
            $clangId = self::$curClangId;
        }

        if (null === $domainId) {
            $domainId = self::getCurrentDomainId();
        }

        $field = self::FIELD_PREFIX . self::getStrippedField($field);

        if (isset(self::$globalValues[$domainId][$clangId][$field])) {
            return self::getEmptyFieldOutput($field, self::$globalValues[$domainId][$clangId][$field], $allowEmpty);
        }

        return self::getEmptyFieldOutput($field, '', $allowEmpty);
    }

    public static function setValue(string $field, ?int $clangId = null, string $value = '', ?int $domainId = null): bool
    {
        if (null === $clangId) {
            $clangId = self::$curClangId;
        }

        if (null === $domainId) {
            $domainId = self::getCurrentDomainId();
        }

        $field = self::FIELD_PREFIX . self::getStrippedField($field);

        // Check if value already exists logically in schema, update it directly and clear cache
        $sql = \rex_sql::factory();
        $sql->setQuery('UPDATE ' . \rex::getTablePrefix() . 'global_settings SET ' . $sql->escapeIdentifier($field) . ' = :value WHERE clang = :clang AND domain_id = :domain', [
            'value' => $value,
            'clang' => $clangId,
            'domain' => $domainId
        ]);
        self::deleteCache();
        return true;
    }

    public static function getDefaultString(string $field, bool $allowEmpty = false, ?int $domainId = null): string
    {
        return (string) self::getDefaultValue($field, $allowEmpty, $domainId);
    }

    public static function getString(string $field, ?int $clangId = null, bool $allowEmpty = false, ?int $domainId = null): string
    {
        return (string) self::getValue($field, $clangId, $allowEmpty, $domainId);
    }

    protected static function getEmptyFieldOutput(string $field, mixed $value, bool $allowEmpty): mixed
    {
        if (!$allowEmpty && '' === $value) {
            return '{{ ' . self::getStrippedField($field) . ' }}';
        }
        return $value;
    }

    public static function getStrippedField(string $field): string
    {
        if (str_starts_with($field, self::FIELD_PREFIX)) {
            $field = substr($field, strlen(self::FIELD_PREFIX));
        }

        return $field;
    }

    public static function getFieldDefinition(string $field): ?array
    {
        $field = self::FIELD_PREFIX . self::getStrippedField($field);
        $sql = \rex_sql::factory();
        $result = $sql->getArray('SELECT * FROM ' . \rex::getTablePrefix() . 'global_settings_field WHERE name = :name', ['name' => $field]);
        return count($result) > 0 ? $result[0] : null;
    }
}
