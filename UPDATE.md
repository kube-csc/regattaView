## Update Anleitung
**Version V00.14.02**
## Presentation
- Ergebnisse und Bahnbelegung werden nun konsequent nach `rennDatum` und `rennUhrzeit` sortiert.
- Bei aktivem Team-Filter werden im Programm nur Rennen mit `status > 1` angezeigt.
- Der Team-Steckbrief blendet Platzhalterwerte wie `PLZ = 99999` und `Ort = nicht angegeben` aus.

## Sprecher 
- Im Team-Steckbrief und in der Sprecheransicht werden Platzhalterwerte wie `PLZ = 99999` und `Ort = nicht angegeben` nicht mehr angezeigt.
- Rennen mit `status = 1` werden als terminlich vorgemerkt behandelt und geben keinen Zustands-Text mehr aus.
- In den Sprecheransichten werden nur bestätigte Rennen mit `status >= 2` angezeigt, wenn die entsprechende Ausgabe aufgerufen wird.

## Presentation
- Neue Option `show_background_image` in `config/presentation.php`:
  - `1` = Präsentations-Hintergrundbild aktiv
  - `0` = Präsentations-Hintergrundbild deaktiviert
- Wenn `show_background_image = 0`, wird die Session-Variable `presentation_background_image` aktiv entfernt und kein neues Hintergrundbild geladen.
- Das Präsentationslayout rendert das Hintergrundbild nur, wenn die Option aktiv ist **und** ein Bild in der Session vorhanden ist.

## Lokale Overrides
- `config/presentation.php` lädt optional `config/presentation_options.php`.
- Lokale Werte werden per `array_replace_recursive(...)` über die Standardwerte gemerged.
- Neue Vorlage `config/presentation_options.example.php` mit allen verfügbaren Parametern (auskommentiert) hinzugefügt.
- `config/presentation_options.php` ist in `.gitignore` eingetragen und wird bei `git pull` nicht überschrieben.

## Installation
- Falls noch nicht vorhanden, `config/presentation_options.php` aus `config/presentation_options.example.php` erzeugen.
- Anpassungen für die Präsentation künftig in `config/presentation_options.php` vornehmen (nicht direkt in `config/presentation.php`).
- Nur lokal abweichende Werte in `config/presentation_options.php` setzen.
- Bei aktiviertem Config-Cache nach Änderungen `php artisan config:clear` ausführen.

## History und Presentation
**Neu: Presentation**
- Zentralisierung aller Anzeigezeiten (Refresh-Intervalle) und Seitenlimits in `config/presentation.php`.
- Umstellung auf dynamische Anzeigezeiten: 8 Sekunden Basiszeit + 1 Sekunde pro realer Zeile/Eintrag.
- Implementierung eines dynamischen Hintergrundbilds (Abteilungsbild aus `sport_sections`), das einmalig pro Session ermittelt wird.
- Verbesserung der Layout-Stabilität für Tabellen durch kontinuierliche Aktualisierung des Maximalwerts (`maximaleTabelleMerk`) auf allen Seiten.
- **Neu: Team-Steckbrief Funktion**
  - Direkter Zugriff über Links in Bahnbelegung und Ergebnislisten (Icon: `bx bx-detail`)
  - Controller: `RaceTeamController@steckbrief` mit Anbindung an `RegattaTeamHistoryService`
  - Zeigt Teamdetails, Teilnahmehistorie und letzte Erfolge aus vergangenen Events
  - Filter: `?finale=1` (Standard, nur Finale) oder `?finale=0` (alle Ergebnisse)
  - Fallback-Bilder aus früheren Regatten via `teamlink`
  - Zyklische Team-Navigation (Zurück/Weiter)
  - Route: `/Regattateam/Steckbrief/{teamId}`

## Installation
- Die Datei `config/presentation.php` muss vorhanden sein. Falls nicht, kann sie aus der Vorlage erstellt werden.

## Header
- Dynamischer Header / Hero-Bereich: Der Header der Anwendung passt sich automatisch an die aufgerufene Domain an.
    - Für jede Domain kann in der Vereinsverwaltung unter dem Menüpunkt **Vereinsserver** ein Hero-Hintergrundbild hinterlegt werden, das als vollflächiges Bild im oberen Seitenbereich erscheint.
    - Zusätzlich kann dort eine Akzentfarbe für Header, Footer-Balken, Buttons und den Zurück-nach-oben-Button festgelegt werden.
    - Sind kein Bild und keine Farbe hinterlegt, greift das Standard-Design.
    - Die Eingabe erfolgt über ein Formular in der Vereinsverwaltung – es sind keine manuellen Datenbankänderungen erforderlich.
Achtung folgendes ist in der .env Datei zu setzen:
    - VEREIN_URL=vereindomain.de
    - Hinweis: Unterstriche in VEREIN_URL werden zu Leerzeichen umgewandelt; Unterstriche vermeiden.

## Installation
- In der Vereinsverwaltung unter **Vereinsserver** die Einstellungen für die gewünschten Domains eintragen (Headerbild und/oder Akzentfarbe).
- Den Laravel-Storage-Symlink einrichten, falls noch nicht vorhanden (`php artisan storage:link`).
- Den Wert `VEREIN_URL` in der `.env`-Datei setzen (keine Unterstriche verwenden).

**Version V00.14.01

## Bugfixes
- Ergebniseinblendung OBS

## Funktionen
- Verbesserung der SlideShow

**Version V00.14.00

## Funktionen
- SlideShow wurde eingebaut: https://[Domain]/Praesentation

## Installation
- Für die Verwaltung der Regatta muss die APP Vereinsverwaltung mindestens V00.10.xx installiert sein.
  [GitHub Projekt Vereinsverwaltung](https://github.com/kube-csc/vereinsverwaltung)

**Version V00.13.01

- composer install
- 
***Neue Funktionen***
- Update auf Laravel 11

**Version V00.13.00**

- Für die Verwaltung der Regatta muss die APP Vereinsverwaltung mindestens  V00.05.xx installiert sein.
  [GitHub Projekt Vereinsverwaltung](https://github.com/kube-csc/vereinsverwaltung)
  zum GitHub Projekt Vereinsverwaltung ab V00.05.xx

**Version V00.12.00**

-  composer install
