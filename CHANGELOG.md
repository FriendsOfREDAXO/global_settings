# Globale Einstellungen - Changelog

## Version 2.6.0
- Replaced data-path by cache @alexplus.de
- Spanish translation @nandes2062 


## Version 2.5.0

Einstellungen können nun auch mit rex_global_settings::setValue() gesetzt werden, Danke @alexplusde

## Version 2.4.1

REX_GLOBAL_VAR rex_escape entfernt, damit HTML-Ausgaben möglich werden.

## Version 2.4.0 

Var= ist nun optional

Es kann nun auch folgende Schreibweise verwendet werden:

REX_GLOBAL_VAR[key]

## Version 2.3.1 

Bugfix Release


## Version 2.3.0 - 08.08.2019

Neu: `REX_GLOBAL_VAR[var=my_field empty=1]`

um leere Felder prüfen zu können. 


## Version 2.2.0 - 25.07.2019

Ab jetzt ein FriendsOfREDAXO-Projekt 

* Neu: Readme mit AutoToc
* Neu `REX_GLOBAL_VAR` liefert das Value des Feldes als String der aktuellen Sprache
* Neu: `rex_global_settings::getFieldDefinition('my_field')` liefert die Felddefinition als Array
* Screenshot hinzugefügt

## Version 2.1.0 - 09. Juni 2018

* Extension Point `GLOBAL_SETTINGS_CHANGED` hinzugefügt. Wird getriggert wenn die Felder oder die Settings aktualisiert wurden
* Focus wird auf erstes Textfeld gelegt beim anlegen/bearbeiten eines Feldes
* Tab Cursor korrigiert, thx@fietstouring
* Addon-Menüeintrag wird nur noch angezeigt wenn der Benutzer das Recht für die Einstellungen-Seite hat, thx@Gort
* File-Cache für die Einstellungen hinzugefügt, benötigt keine DB Abfragen mehr wenn einmal gecachet
* Ausgabe der Codebeispiele für REDAXO 5.6 wiederhergestellt

## Version 2.0.0 - 15. März 2017

* Portierung zu REDAXO 5
* Neuer Feldtyp: Tab, dadurch kann man das AddOn auch als String Table oder Sprog ersatz benutzen. Die Feldbezeichnung können auch leer gelassen werden, dann wird direkt der Feldname (Spaltenname) dem Enduser angezeigt.
* Neuer Feldtyp: Colorpicker (siehe Readme für Hinweise)
* Der `glob_` Prefix ist jetzt optional. Aufruf sollte so erfolgen: `rex_global_settings::getValue('my_field');`. Beim Feldanlegen sollte ebenfalls kein `glob_` benutzt werden.
* Hinzugefügt: `rex_global_settings::getString()` und `rex_global_settings::getDefaultString()`. Wie `getValue()` nur dass standardmäßig ein Platzhalter angezeigt wird wenn Ausgabe leer ist.
* Ein dritter Parameter `$allowEmpty` für `getValue()` und `getString()` wurde hinzugefügt der steuert ob ein Platzhalter angezeigt wird wenn Feld leer oder nicht da. `getValue()` Standard: nicht anzeigen, `getString()` Standard: anzeigen

## Version 1.1.0 - 01. März 2016

* Fixed #10: Checkboxen gingen nicht, specialthx@Sysix
* Fixed #11: Wenn Feld nicht vorhanden war gab es eine Fehlermeldung, specialthx@Sysix
* Kategorie-Checkbox entfernt, da keine Funktion
* Fixed: Database down Problem wenn REDAXO Setup gestartet wurde
* Fixed #8: Felder wurden nicht korrekt ausgelesen unter PHP 5.3

## Version 1.0.1 - 20. August 2015

* Englische Backend Übersetzung hinzugefügt

## Version 1.0.0 - 11. August 2015

