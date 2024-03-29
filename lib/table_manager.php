<?php

class rex_global_settings_table_manager
{
    private $tableName;
    private $DBID;

    public function __construct($tableName, $DBID = 1)
    {
        $this->tableName = $tableName;
        $this->DBID = $DBID;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function addColumn($name, $type, $length, $default = null, $nullable = true)
    {
        $qry = 'ALTER TABLE `' . $this->getTableName() . '` ADD ';
        $qry .= '`' . $name . '` ' . $type;

        if (0 != $length) {
            $qry .= '(' . $length . ')';
        }

        if (null !== $default) {
            $qry .= ' DEFAULT \'' . str_replace("'", "\\'", $default) . '\'';
        }

        if (true !== $nullable) {
            $qry .= ' NOT NULL';
        }

        try {
            $this->setQuery($qry);
            return true;
        } catch (rex_sql_exception $e) {
            return false;
        }
    }

    public function editColumn($oldname, $name, $type, $length, $default = null, $nullable = true)
    {
        $qry = 'ALTER TABLE `' . $this->getTableName() . '` CHANGE ';
        $qry .= '`' . $oldname . '` `' . $name . '` ' . $type;

        if (0 != $length) {
            $qry .= '(' . $length . ')';
        }

        if (null !== $default) {
            $qry .= ' DEFAULT \'' . str_replace("'", "\\'", $default) . '\'';
        }

        if (true !== $nullable) {
            $qry .= ' NOT NULL';
        }

        try {
            $this->setQuery($qry);
            return true;
        } catch (rex_sql_exception $e) {
            return false;
        }
    }

    public function deleteColumn($name)
    {
        $qry = 'ALTER TABLE `' . $this->getTableName() . '` DROP ';
        $qry .= '`' . $name . '`';

        try {
            $this->setQuery($qry);
            return true;
        } catch (rex_sql_exception $e) {
            return false;
        }
    }

    public function hasColumn($name)
    {
        $columns = rex_sql::showColumns($this->getTableName(), $this->DBID);

        foreach ($columns as $column) {
            if ($column['name'] == $name) {
                return true;
            }
        }
        return false;
    }

    protected function setQuery($qry)
    {
        try {
            $sql = rex_sql::factory($this->DBID);
            $sql->setQuery($qry);
            return true;
        } catch (rex_sql_exception $e) {
            return false;
        }
    }
}
