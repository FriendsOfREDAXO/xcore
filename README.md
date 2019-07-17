X-Core für REDAXO 5
===================

SEO Verbesserungen, Url Manipulation, Tweaks, Code Snippets Sammlung, Extra Stuff und die rexx API. Nachfolger von SEO42.

Features
--------

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
* PHP Custom Frontend Form ohne yForm, Multi Slice Module und weitere Code Snippets

Verfügbare Sprachumschreibungen
-------------------------------

Die Urls werden mittels dieser LangPresets automatisch sauber umgeschrieben wenn die Codes der Sprachen unter REDAXO > System > Sprachen korrekt eingegeben wurden.

* české (cs)
* dansk (da)
* deutsch (de)
* english (en)
* español (es)
* française (fr)
* italiano (it)
* magyar (hu)
* nederlands (nl)
* norsk (no)
* polska (pl)
* português (pt)
* svensk (sv)
* türk (tr)

500 Serverfehler
----------------

Wenn Sie einen 500 Serverfehler bekommen entfernen Sie die Zeile `Options -Indexes` aus der `.htaccess` Datei im root Verzeichnis.


Fehlerartikel
-------------

Stellen Sie einen Fehlerartikel ein. Sonst liefert X-Core eine eigene 404 Seite aus um Double Content Probleme zu vermeiden.

Unterordner-Installationen
--------------------------

Unter REDAXO > System ist es wichtig den Unterordner bei der URL der Website mit anzugeben. Wenn Sie unter yRewrite > Domains eine Domain/Url eingestellt haben sollte diese ebenfalls mit Unterordner angegeben werden.

Dokumentation
-------------

* [rexx API](docs/rexx_api.md)
* [Less.php](http://lessphp.typesettercms.com/)
* [scssphp](http://leafo.net/scssphp/docs/)
* [Simple Html Dom](http://simplehtmldom.sourceforge.net/)
* [Parsedown](https://github.com/erusev/parsedown/wiki)

Hinweise
--------

* Getestet mit REDAXO 5.3
* AddOn-Ordner lautet: `xcore`
* Abhängigkeiten: yRewrite

Changelog
---------

siehe `CHANGELOG.md` des AddOns

Lizenz
------

MIT-Lizenz, siehe `LICENSE.md` des AddOns

Credits
-------

* PHP-Markdown-Documentation-Generator, itsliamjones (threeWayComparison()), Public Domain Logo, More to come...
