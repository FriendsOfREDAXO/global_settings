<?php 
/**
 * This file is part of the global_settings package.
 *
 * @author (c) Friends Of REDAXO
 * @author <friendsof@redaxo.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class rex_var_global_var extends rex_var
{
    protected function getOutput()
    {
        $var = $this->getParsedArg('var', null, true);
        if (null === $var) {
            return false;
        }

        if ($this->getArg('empty') == '1') {
            $method = 'getDefaultValue';
        } else {
            $method = 'getString';
        }

        return "rex_global_settings::$method($var)";
    }
}
