<?php

class rex_global_settings
{
    protected static $globalValues = [];
    protected static $curClangId;
    protected static $defaultClang;
    protected static $cacheFile;

    const FIELD_PREFIX = 'glob_';
    const CACHE_FILENAME = 'cache.php';

    public static function init()
    {
        self::$curClangId = rex_clang::getCurrentId();
        self::$defaultClang = rex_clang::getStartId();
        self::$cacheFile = rex_path::addonCache('global_settings', self::CACHE_FILENAME);

        if (file_exists(self::$cacheFile)) {
            // retrieve from cache
            self::$globalValues = include(self::$cacheFile);
        } else {
            // retrieve from db
            $sql = rex_sql::factory();
            $result = $sql->getArray('SELECT * FROM ' . rex::getTablePrefix() . 'global_settings');

            if (is_array($result)) {
                // build globalValues array based on clang then key/value
                for ($i = 0; $i < count($result); $i++) {
                    $clangId = $result[$i]['clang'];
                    unset($result[$i]['clang']);

                    self::$globalValues[$clangId] = $result[$i];
                }

                // create cache dir if necessary
                $dataCache = rex_path::addonCache('global_settings');

                if (!file_exists($dataCache)) {
                    if (!mkdir($dataCache, rex::getDirPerm(), true)) {
                        throw new Exception('Dir "' . $dataCache . '" could not be created! Check if server permissions are set correctly.');
                    }
                }

                // store in cachefile for next time
                if (!file_put_contents(self::$cacheFile, '<?php return ' . var_export(self::$globalValues, true) . ';')) {
                    throw new Exception('File "' . self::$cacheFile . '" could not be written! Check if server permissions are set correctly.');
                }
            }
        }
    }

    public static function deleteCache()
    {
        if (file_exists(self::$cacheFile)) {
            return unlink(self::$cacheFile);
        }

        return false;
    }

    public static function getDefaultValue($field, $allowEmpty = true)
    {
        return self::getValue($field, self::$defaultClang, $allowEmpty);
    }

    public static function getValue($field, $clangId = null, $allowEmpty = true)
    {
        if ($clangId == null) {
            $clangId = self::$curClangId;
        }

        $field = self::FIELD_PREFIX . self::getStrippedField($field);

        if (isset(self::$globalValues[$clangId][$field])) {
            return self::getEmptyFieldOutput($field, self::$globalValues[$clangId][$field], $allowEmpty);
        }

        return self::getEmptyFieldOutput($field, '', $allowEmpty);
    }

    public static function setValue($field, $clangId = null, $value = "")
    {
        if ($clangId == null) {
            $clangId = self::$curClangId;
        }

        $field = self::FIELD_PREFIX . self::getStrippedField($field);

        if (isset(self::$globalValues[$clangId][$field])) {
            rex_sql::factory()->setDebug(0)->setQuery('UPDATE ' . rex::getTablePrefix() . 'global_settings SET ' . $field . ' =  :value WHERE clang = :clang', [':value' => $value, ':clang' => $clangId]);
            rex_global_settings::deleteCache();
            return true;
        } else {
            return false;
        }
    }

    public static function getDefaultString($field, $allowEmpty = false)
    {
        return self::getDefaultValue($field, $allowEmpty);
    }

    public static function getString($field, $clangId = null, $allowEmpty = false)
    {
        return self::getValue($field, $clangId, $allowEmpty);
    }

    protected static function getEmptyFieldOutput($field, $value, $allowEmpty)
    {
        if (!$allowEmpty && $value == '') {
            return '{{ ' . self::getStrippedField($field) . ' }}';
        } else {
            return $value;
        }
    }

    public static function getStrippedField($field)
    {
        if (strpos($field, self::FIELD_PREFIX) === 0) {
            $field = substr($field, strlen(self::FIELD_PREFIX));
        }

        return $field;
    }

    public static function getFieldDefinition($field)
    {
        $field = self::FIELD_PREFIX . self::getStrippedField($field);
        $sql = rex_sql::factory();
        $result = $sql->getArray('SELECT * FROM ' . rex::getTablePrefix() . 'global_settings_field WHERE name = :name', ['name' => $field]);
        return $result[0];
    }
}

