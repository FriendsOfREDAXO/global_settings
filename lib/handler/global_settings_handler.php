<?php

class rex_global_settings_global_settings_handler extends rex_global_settings_handler
{
    const PREFIX = 'glob_';

    protected function handleSave(array $params, rex_sql $sqlFields)
    {
        // Nur speichern wenn auch das MetaForm ausgefüllt wurde
        // z.b. nicht speichern wenn über be_search select navigiert wurde
        if (!rex_post('savemeta', 'boolean')) {
            return $params;
        }

        $article = rex_sql::factory();
        // $article->setDebug();
        $article->setTable(rex::getTablePrefix() . 'global_settings');
        $article->setWhere('clang=:clang', ['clang' => $params['clang']]);
        //$article->setValue('name', rex_post('meta_article_name', 'string'));

        parent::fetchRequestValues($params, $article, $sqlFields);

        // do the save only when metafields are defined
        if ($article->hasValues()) {
            $article->update();
        }

        //rex_article_cache::deleteMeta($params['id'], $params['clang']);

        rex_extension::registerPoint(new rex_extension_point('GLOB_META_UPDATED', '', $params));

        return $params;
    }

    protected function buildFilterCondition(array $params)
    {
        $restrictionsCondition = '';

        if (!empty($params['id'])) {
            $s = '';
            $OOArt = rex_article::get($params['id'], $params['clang']);

            // Alle Metafelder des Pfades sind erlaubt
            foreach ($OOArt->getPathAsArray() as $pathElement) {
                if ($pathElement != '') {
                    $s .= ' OR `p`.`restrictions` LIKE "%|' . $pathElement . '|%"';
                }
            }

            $restrictionsCondition = 'AND (`p`.`restrictions` = "" OR `p`.`restrictions` IS NULL ' . $s . ')';
        }

        return $restrictionsCondition;
    }

    protected function renderFormItem($field, $tag, $tag_attr, $id, $label, $labelIt, $typeLabel)
    {
        return $field;
    }

    public function getForm(array $params)
    {
        //$OOArt = rex_article::get($params['id'], $params['clang']);

        //$params['activeItem'] = $params['article'];
        // Hier die category_id setzen, damit beim klick auf den REX_LINK_BUTTON der Medienpool in der aktuellen Kategorie startet
        //$params['activeItem']->setValue('category_id', $OOArt->getCategoryId());

	 $sql = rex_sql::factory();
        //$sql->setDebug();
        $sql->setTable(rex::getTablePrefix() . 'global_settings');
        $sql->setWhere('clang=:clang', ['clang' => $params['clang']]);
		$sql->select('*');
		$params['activeItem'] = $sql;
		$params['activeItem']->setValue('category_id', 0); // othewise notice

        return parent::renderFormAndSave(self::PREFIX, $params);
    }

    public function extendForm(rex_extension_point $ep)
    {
        // noop
    }
}
