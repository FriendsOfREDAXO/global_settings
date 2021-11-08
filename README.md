# Globale Einstellungen, AddOn für REDAXO 5

Mit diesem Addon kann man globale MetaInfos setzen, die für die gesamte Website gültig sind. Admins können Felder anlegen und bearbeiten, Nicht-Admins können diese nur bearbeiten

![Screenshot](https://github.com/FriendsOfREDAXO/global_settings/raw/assets/screenshot.png)
(Beispiel)

## Features

* MetaInfos für die gesamte Website
* API für den Zugriff auf die Felder
* Nicht-Admins dürfen Felder nur bearbeiten
* Mehrsprachigkeit
* Neue Feldertypen: Tab, Colorpicker

## Tabs

Das AddOn kann Felder in Tabs gruppieren. Hier ein Beispiel für eine mögliche Gruppierung in 3 Tabs:

* Allgemein (mit allgemeinen Feldern)
* Tracking Code (Textarea mit class="codemirror", wenn installiert)
* Übersetzungen (mit Text-Felder wie bei Sprog oder beim String Table Addon für R4).

## Colorpicker

* Der eingsetzte Colorpicker ist dieser hier: https://bgrins.github.io/spectrum/ 
* Alle Optionen lassen sich auch per data-Attribut festlegen (einzugeben in Globale Einstellungen > Felder > Feldattribute), siehe den Tip hier https://bgrins.github.io/spectrum/#options
* Beispiel: `data-preferred-format="rgb" data-show-alpha="true"` zeigt rgba Werte an inkl. Alpha-Transparenzen.

## API

```php
// Ausgabe eines Feldes der aktuellen Sprache
echo rex_global_settings::getValue('my_field');

// Ausgabe eines Feldes der Sprache mit der ID = 2
echo rex_global_settings::getValue('my_field', 2);

// Ausgabe eines Feldes der Haupt-Sprache
echo rex_global_settings::getDefaultValue('my_field');

// Ausgabe eines Feldes der aktuellen Sprache, wenn leer kommt Ausgabe {{ my_field }}
echo rex_global_settings::getString('my_field');

// Ausgabe eines Feldes der Sprache mit der ID = 2, wenn leer kommt Ausgabe {{ my_field }}
echo rex_global_settings::getString('my_field', 2);

// Ausgabe eines Feldes der Haupt-Sprache, wenn leer kommt Ausgabe {{ my_field }}
echo rex_global_settings::getDefaultString('my_field');

// Ausgabe der Felddefinition als Array
dump(rex_global_settings::getFieldDefinition('my_field'));

// Überschreiben eines Feldwertes der aktuellen Sprache mit dem Wert "Hallo"  
rex_global_settings::setValue('my_field', null, "Hallo");

// Überschreiben eines Feldwertes der Sprache mit der ID = 2 mit dem Wert "Hallo"  
rex_global_settings::setValue('my_field', 2, "Hallo");
```

## REDAXO-Variable

Die REDAXO-Variable `REX_GLOBAL_VAR` kann in Modulen und Templates verwendet werden um Werte auszulesen. 
Sie entspricht der Ausgabe von: `rex_global_settings::getString('my_field')`. 

Verwendung: 

```
REX_GLOBAL_VAR[my_field]
```

```
REX_GLOBAL_VAR[var=my_field]
```

Benötigt man einen leeren Rückgabewert für Prüfungen

```
REX_GLOBAL_VAR[var=my_field empty=1]
```

## Hinweise

* Addon kann als String Table / Sprog Ersatz genutzt werden durch Einsatz der Tabs. Die Feldbezeichnung können auch leer gelassen werden, dann wird direkt der Feldname (Spaltenname) dem Enduser angezeigt.

## Changelog

siehe `CHANGELOG.md` des AddOns

## Lizenz

MIT-Lizenz, siehe `LICENSE.md` des AddOns und Release notes

## Credits

* REXDude
* Spectrum Color Picker
* Global Settings ist ein Fork des Meta Info Addons
* @eaCe
* @Sysix
* @polarpixel
* @skerbis
* @alxndr-w

