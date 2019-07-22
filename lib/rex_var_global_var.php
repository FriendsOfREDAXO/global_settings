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
    $out = '';   
       if ($this->hasArg('var') && $this->getArg('var')) {
       $out =  rex_global_settings::getString($this->getArg('var'));
    }
	return self::quote($out);
   }
}
