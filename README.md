# Allgemeine Informationen
Als Vorlage habe ich das Beispiel-Projekt von Slim Framework hergenommen und dann selber noch ein paar Ordner hinzugefügt. 
* https://github.com/slimphp/Tutorial-First-Application


# Set-Up
* Damit alles funktioniert, muss man eine Datenbank erstellen, mit den Namen `filehoster`, und eine Tabelle mit den Namen `dateiinfo`
* In der root-directory liegt eine Datei mit den SQL-Statements, mit denen man die Datenbank erstellen kann.
* Die Zugangsdaten können in `lib\Core.php` eingegeben werden.
* In der Konfigurationsdatei des Servers soll der `DocumentRoot` auf dem public Ordner verweisen.


# Initialisierung 
* Ruft man localhost auf, wird die index.php aufgerufen, welche dann alles initialisiert und die Routers ladet.
* Danach wird ein Router der in den Ornder `routers\` liegt aufgerufen.

# Index.html
* Gibt man der Index.html keine Datei zum raufladen, wird `no file added` ausgegben.
* Gibt man der Index.html nur die Datei, und gibt für die Speicherdauer und fürs Passwort nichts ein, wird die Datei für immer gespeichert und man braucht keine Passwort um sie herunterzuladen
* Gibt man der Index.html eine Datei, die Speicherdauer (in Minuten), und ein Passwort, ist die Datei nur für eine bestimmte Zeit gespeichert. Nach dem diese Zeit abgelaufen ist, erscheint eine Fehlermeldung beim Downloaden.
