X-Core - Changelog
==================

### Version 1.1.4 - 13. November 2019

* htaccess an aktuelle Regeln von YForm angepasst
* Slowenisches Sprachschema hinzugefügt
* Optionale Features per default deaktiviert


### Version 1.1.3 - 09. Juni 2018

* `rexx_markdown` Klasse aktualisiert und REDAXO 5.6 compat hergestellt
* YRewrite Titel Placeholder entfernt da zum X-Core Titelschema nicht passend

### Version 1.1.2 - 09. Juni 2018

* Speicherung der Settings in REDAXO 5.6 wieder ermöglicht
* CSS für Code Snippet "Frontend Form" ergänzt
* z-index erhöht für 404 Offline Message Balken, thx@fietstouring
* Exception in `rexx::isSliceOfSameType()` gefixed, thx@helpy
* Bei Benutzung von `SANITIZE_TYPE_INT` trat ein Fehler auf
* Die Resource Pfade können jetzt zur Laufzeit verändert werden über die entsprechenden getter/setter
* `rexx::stringReplaceLast()` hinzugefügt, ersetzt nur letztes Vorkommen eines Strings
* `rexx::isUserValid()` hinzugefügt, prüft ob User Objekt gültig ist
* `SANITIZE_TYPE_RAW` für die Frontend Form hinzugefügt

### Version 1.1.1 - 10. April 2017

* Code Snippet hinzugefügt: Multi Slice Module
* `rexx::getResourceFile()` kompiliert jetzt auch LESS/SCSS Dateien

### Version 1.1.0 - 09. April 2017

* PHP Custom Frontend Form ohne yForm hinzugefügt
* Code Snippets hinzugefügt: Boilerplate, Resource Includer und Frontend Form
* SCSS Compiler auf Version v0.6.7 aktualisiert
* X-Core Styles: Extra MForm Tabs Style entfernt
* X-Core Styles: MForm Checkbox wieder ausgerichtet
* X-Core Styles: Manche Textareas waren verschwunden (z.B. yForm > Email-Templates) bei ausgeschaltetem Codemirror
* Readme: Hinweis zu Unterordner-Installationen hinzugefügt
* rexx API: `rexx::sortArticles()` hinzugefügt, thx@fietstouring
* rexx API: `rexx::formatBytes()` hinzugefügt, gibt Bytes z.b. von `filesize()` formatiert aus z.B. 20,53 MB.
* rexx API: `rexx::threeWayComparison()` hinzugefügt, PHP5 Ersatz für Spaceship Operator `<=>` aus PHP 7
* rexx API: `rexx::sanitizeFormValue()`, `rexx::validateFormValue()` und weitere Helfer für den Custom Frontend Form Code hinzugefügt

### Version 1.0.0 - 15. März 2017

Erste Version mit folgenden Features:

* Sauber eingestelltes Caching sowie Komprimierung für Resourcen wie Bildern, Fonts, CSS und JS Dateien (.htaccess)
* Verschiedene URL-Endungen einstellbar (z.B. Endung `.html` oder `/`)
* Automatische Titel-Generierung. Mitgeliefertes Titel-Schema aus [Google-PDF](http://www.google.de/webmasters/docs/einfuehrung-in-suchmaschinenoptimierung.pdf) entnommen.
* Suchmaschinenfreundliche Media Manager Urls durch Verwendung der verfügbaren PHP-Methode `rexx::getManagedMediaFile()`
* Force Download Funktionalität inkl. suchmaschinenfreundlicher URLs und Canonical Header (z.B. für PDF Downloads) durch Verwendung von `rexx::getDownloadFile()`
* LangPresets: Spezielle sprachabhängige sowie sprachunabhängige Sonderzeichen-Umschreibungen einstellbar. Aktuell werden 14 Sprachen out-of-the-box unterstützt
* Smart Redirects: Automatische Umleitungen für falsch eingegebene Urls z.B. von Url-Endung `/` nach `.html`
* Offline 404 Modus: Offline Artikel sind für nicht eingeloggte Benutzer nicht mehr erreichbar (404 Seite)
* Automatisches setzen der Locale in PHP um z.B. Monatsnamen/Datumsangaben in der richtigen Sprache zu erhalten (z.b. über `strftime`)
* Automatisches senden von Header wie `X-UA-Compatible` oder einen `Cache-Control` Header für manche Server die Media Manager Bilder sonst nicht korrekt cachen würden
* Bei gleichem Startartikel wie Fehlerartikel (nicht empfohlen!) wird ein 404 gesendet (was REDAXO sonst nicht tut). Desweiteren liefert X-Core in diesem Fall eine eigene 404 Seite aus um Double Content Probleme zu vermeiden.
* rexx API: Umfangreiche API a la seo42 Klasse (diesmal) inkl. Dokumentation
* Mitgeliefertes Boilerplate (Template) welches die rexx API benutzt
* Resourcen Handling: Kombinieren von mehreren CSS / JS Dateien zu einer. Senden eines Versionsstrings (Cache-Buster) usw.
* Mitgelieferter und integrierter LESS sowie SCSS Compiler
* X-Core Extra Styles, Backend ohne grün sowie REDAXO Logo Flicker Fixer
* JS Tools wie der Panel Toggler und Persistent Tabs für die Speicherung der Boostrap Tabposition (über die CSS Klasse `rexx-persistent-tabs` einstellbar)
* Frontend Link "zur Website" in der Metanavigation
* Klasse `rexx_markdown`: modifizierte Parsedown Klasse die Syntax Highlighting von Codeblöcken in MD Dateien unterstützt
* Klasse `rexx_simple_html_dom`: Wrapper für die mitgelieferte Simple Html Dom Bibliothek zum einfachen manipulieren des HTML Doms per PHP

