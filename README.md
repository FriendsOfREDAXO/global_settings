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

Ab Version 3.0.0 verwendet das AddOn PSR-4 Namespaces: `FriendsOfRedaxo\GlobalSettings`.
Alle statischen Aufrufe erfolgen über `\FriendsOfRedaxo\GlobalSettings\GlobalSettings`.

```php
use FriendsOfRedaxo\GlobalSettings\GlobalSettings;

// Ausgabe eines Feldes der aktuellen Sprache
echo GlobalSettings::getValue('my_field');

// Ausgabe eines Feldes der Sprache mit der ID = 2
echo GlobalSettings::getValue('my_field', 2);

// Ausgabe eines Feldes der Haupt-Sprache
echo GlobalSettings::getDefaultValue('my_field');

// Ausgabe eines Feldes der aktuellen Sprache, wenn leer kommt Ausgabe {{ my_field }}
echo GlobalSettings::getString('my_field');

// Ausgabe eines Feldes der Sprache mit der ID = 2, wenn leer kommt Ausgabe {{ my_field }}
echo GlobalSettings::getString('my_field', 2);

// Ausgabe eines Feldes der Haupt-Sprache, wenn leer kommt Ausgabe {{ my_field }}
echo GlobalSettings::getDefaultString('my_field');

// Ausgabe der Felddefinition als Array
dump(GlobalSettings::getFieldDefinition('my_field'));

// Überschreiben eines Feldwertes der aktuellen Sprache mit dem Wert "Hallo"  
GlobalSettings::setValue('my_field', null, "Hallo");

// Überschreiben eines Feldwertes der Sprache mit der ID = 2 mit dem Wert "Hallo"  
GlobalSettings::setValue('my_field', 2, "Hallo");

// --- YRewrite Domain-Support ---
// Wenn YRewrite installiert ist, wird standardmäßig die aktuelle Domain herangezogen.
// Ist YRewrite nicht installiert, gibt es keine Domain-Unterscheidung. Alle Werte gelten systemweit und werden intern unter der Tabellen-ID 1 gespeichert.
// Man kann über den vierten Parameter gezielt eine spezifische Domain-ID abfragen (z.B. bei Multi-Domain Setups).

// Ausgabe eines Feldes der aktuellen Sprache und einer spezifischen Domain (ID = 2)
// (3. Parameter ist für "allowEmpty", standard bei getValue ist true)
echo GlobalSettings::getValue('my_field', null, true, 2);

// Ausgabe als formatierten String der aktuellen Sprache und spezifischen Domain (ID = 2)
// (3. Parameter ist für "allowEmpty", standard bei getString ist false -> Rückgabe "{{ my_field }}")
echo GlobalSettings::getString('my_field', null, false, 2);
```

## REDAXO-Variable

Die REDAXO-Variable `REX_GLOBAL_VAR` kann in Modulen und Templates verwendet werden um Werte auszulesen. 
Sie entspricht der Ausgabe von: `\FriendsOfRedaxo\GlobalSettings\GlobalSettings::getString('my_field')`. 

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

