## Update Anleitung
**Version V00.14.02**

#Funktion
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

#Installation
- Die Datei `config/presentation.php` muss vorhanden sein. Falls nicht, kann sie aus der Vorlage erstellt werden.

**Version V00.14.01

#Bugfixes
- Ergebniseinblendung OBS

#Funktion
- Verbesserung der SlideShow

**Version V00.14.00

#Funktion
- SlideShow wurde eingebaut: https://[Domain]/Praesentation

#Installation
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
