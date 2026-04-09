<?php

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Handler\Handler instead */
abstract class rex_global_settings_handler extends \FriendsOfRedaxo\GlobalSettings\Handler\Handler {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Handler\GlobalSettingsHandler instead */
class rex_global_settings_global_settings_handler extends \FriendsOfRedaxo\GlobalSettings\Handler\GlobalSettingsHandler {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\TableExpander instead */
class rex_global_settings_table_expander extends \FriendsOfRedaxo\GlobalSettings\TableExpander {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\TableManager instead */
class rex_global_settings_table_manager extends \FriendsOfRedaxo\GlobalSettings\TableManager {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Input\Input instead */
abstract class rex_global_settings_input extends \FriendsOfRedaxo\GlobalSettings\Input\Input { public function getHtml() { return ""; } }

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Input\Colorpicker instead */
class rex_global_settings_input_colorpicker extends \FriendsOfRedaxo\GlobalSettings\Input\Colorpicker {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Input\Date instead */
class rex_global_settings_input_date extends \FriendsOfRedaxo\GlobalSettings\Input\Date {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Input\Datetime instead */
class rex_global_settings_input_datetime extends \FriendsOfRedaxo\GlobalSettings\Input\Datetime {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Input\Linkbutton instead */
class rex_global_settings_input_linkbutton extends \FriendsOfRedaxo\GlobalSettings\Input\Linkbutton {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Input\Linklistbutton instead */
class rex_global_settings_input_linklistbutton extends \FriendsOfRedaxo\GlobalSettings\Input\Linklistbutton {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Input\Mediabutton instead */
class rex_global_settings_input_mediabutton extends \FriendsOfRedaxo\GlobalSettings\Input\Mediabutton {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Input\Medialistbutton instead */
class rex_global_settings_input_medialistbutton extends \FriendsOfRedaxo\GlobalSettings\Input\Medialistbutton {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Input\Rgbacolorpicker instead */
class rex_global_settings_input_rgbacolorpicker extends \FriendsOfRedaxo\GlobalSettings\Input\Rgbacolorpicker {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Input\Select instead */
class rex_global_settings_input_select extends \FriendsOfRedaxo\GlobalSettings\Input\Select {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Input\Text instead */
class rex_global_settings_input_text extends \FriendsOfRedaxo\GlobalSettings\Input\Text {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Input\Textarea instead */
class rex_global_settings_input_textarea extends \FriendsOfRedaxo\GlobalSettings\Input\Textarea {}

/** @deprecated Use \FriendsOfRedaxo\GlobalSettings\Input\Time instead */
class rex_global_settings_input_time extends \FriendsOfRedaxo\GlobalSettings\Input\Time {}
/** @deprecated Wait, they were never migrated to PSR-4? Let's dummy them out too! */
class rex_global_settings_input_checkbox extends \FriendsOfRedaxo\GlobalSettings\Input\Input { public function getHtml() { return ""; } }
class rex_global_settings_input_radio extends \FriendsOfRedaxo\GlobalSettings\Input\Input { public function getHtml() { return ""; } }
class rex_global_settings_input_categoryselect extends \FriendsOfRedaxo\GlobalSettings\Input\Input { public function getHtml() { return ""; } }
class rex_global_settings_input_mediacategoryselect extends \FriendsOfRedaxo\GlobalSettings\Input\Input { public function getHtml() { return ""; } }
