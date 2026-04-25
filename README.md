<h1>Präsentation von Regatten</h1>
<p>Version: V00.14.02</p>
<p>
Eingesetzt z.B. für die Rennsport- und Drachenbootregatta eines Kanu-Vereins
</p>

<a href="https://live.kel-datteln.de">Beispiel einer Regatta</a>

<h2>Installierte Programme</h2>
<ul>
  <li><a href="https://laravel.com/docs/11.x" target="_blank">Laravel 11.*</a>
  <li><a href="https://bootstrapmade.com/squadfree-free-bootstrap-template-creative/" target="_blank">BootstrapMade.com </a></li>
  <li><a href="https://boxicons.com/" target="_blank">boxicons</a></li>
<li>.htaccess für ionos.de Server</li>
</ul>

<h2>Benötigte Lizenzen</h2>
Es wird eine Lizenz für
<a href="https://bootstrapmade.com/squadfree-free-bootstrap-template-creative/">Squadfree von bootstrapmade</a>
benötigt.

<h2>Frontend</h2>
<ul>
    <li>Leanding Page
        <ul>
            <li>Beschreibung der Events</li> 
            <li>Dokumente zum Herunterladen
              <ul>
                <li>Ausschreibungsunterlagen</li>
                <li>Programmunterlagen</li>
                <li>Ergebnislisten</li>
                <li>Flyer / Plakat</li>
              </ul>
            </li>
            <li>Präsentation der Teams der Regatta</li>
            <li>Kontakt des veranstaltenden Vereins inc. Map</li>
        </ul> 
    </li>
    <li>Liste der Rennen nach Zeitplan incl. Verspätungen der Startzeit
      <ul>
        <li>Alle Rennen</li>
        <li>verloste Rennen</li>
        <li>gewertete Rennen</li>
        <li>Tabellen</li>
        <li>Team-Steckbriefe
          <ul>
            <li>Direkter Zugriff über Links in Bahnbelegung und Ergebnislisten</li>
            <li>Teamdetails: Verein, Rennklasse, Bootsklasse, Ort</li>
            <li>Teilnahmestatistik und letzte Erfolge aus vergangenen Events</li>
            <li>Umschaltbarer Filter: nur Finale oder alle Ergebnisse</li>
            <li>Fallback-Bilder aus früheren Regatten</li>
            <li>Navigation durch alle Teams des aktuellen Events (zyklisch)</li>
          </ul>
        </li>
      </ul> 
    </li>
    <li>Footer
      <ul>
        <li>Links zu Webseiten der Abteilungen/Sportgruppen</li>
        <li>Impressum</li>
        <li>Datenschutzerklärung</li>
      </ul>
    </li>
</ul>

<h2>Live Einblendung z.B. OBS</h2>
<p>
Es gibt URL's für sie Einblendung für aktuelle Renninformationen. Diesen können z.B. in OBS eingebunden werden.
Es wird immer die aktuellen Daten eingetragen, wenn die Ergebnisse zeitgleichen mit den aktullen Rennen erfolgt.
Alternativ kann ein Rennen auch als aktuell in der Regattaverwaltung angepint werden.
</p>
<ul>
    <li>http://[Domain]/OBSLive/Ergebniss</li>
    <li>http://[Domain]/OBSLive/Ergebnissall</li>
    <li>http://[Domain]/OBSLive/Bahnbelegung</li>
    <li>http://[Domain]/OBSLive/Naechstesrennen</li>
</ul>

<h2>Team-Steckbrief</h2>
<p>
  Der Team-Steckbrief bietet eine detaillierte Übersicht über ein Team mit seinen Erfolgen und Statistiken.
  Er wird direkt aus der Bahnbelegung oder Ergebnisliste über das Symbol <code>bx bx-detail</code> aufgerufen.
</p>
<ul>
  <li><strong>Verfügbar unter:</strong> <code>http://[Domain]/Regattateam/Steckbrief/{teamId}</code></li>
  <li><strong>Datenquellen:</strong>
    <ul>
      <li>Aktuelle Teaminformationen aus dem aktuellen Event</li>
      <li>Teilnahmehistorie: Anzahl vergangener Teilnahmen in der gleichen Bootsklasse</li>
      <li>Erfolgsstatistik: letzte Platzierungen aus abgeschlossenen Regatten</li>
    </ul>
  </li>
  <li><strong>Filter:</strong> <code>?finale=1</code> (Standard, nur Finale) oder <code>?finale=0</code> (alle Ergebnisse)</li>
  <li><strong>Navigation:</strong> zyklische Navigation durch alle Teams des aktuellen Events (Zurück/Weiter)</li>
</ul>

<h2>Live Sprecherinformation</h2>
<p>
Es gibt die möglichkeit, dass der Sprecher die Informationen über die Rennen auf einem Tablet oder Smartphone einsehen kann.
Die Url hierfür ist:
</p>
<ul>
    <li>http://[Domain]/Sprecher</li>
</ul>

<h2>SlideShow</h2>
<p>
Die SlideShow Funktion ermöglicht die automatische Präsentation von Ergebnissen, Tabellen und Livestreams auf einem Bildschirm. Einzelne Rennen oder Tabellen können gezielt für die Präsentation markiert werden. Die Anzeige wechselt automatisch zwischen den verschiedenen Ansichten.<br>
Sie wird unter <b>http://[Domain]/Praesentation</b> abgerufen werden.<br>
Ein Neustart der Präsentation ist mit <b>http://[Domain]/Praesentation/Start</b> möglich.<br>
Die Anzeigezeiten und Seitenlimits (z.B. Teams pro Seite) können zentral in der Datei <b>config/presentation.php</b> angepasst werden.
</p>

<ul>
  <li>Willkommensseite (Welcome)</li>
  <li>Informationsseite (Information)</li>
  <li>Ergebnisseite (Ergebnisse)</li>
  <li>Bahnaufstellung (Lane Occupancy)</li>
  <li>Teamprofile (Teamprofile)</li>
  <li>Automatische Anzeige neuer Rennergebnisse, sobald diese markiert wurden</li>
  <li>Automatische Anzeige neuer Tabellen, wenn diese für die Präsentation markiert wurden</li>
  <li>Automatischer Start eines Livestreams, wenn dieser aktiviert wurde</li>
  <li>Anzeige eines hinterlegten Videos in der Präsentation</li>
</ul>

<h2>Backend</h2>
<p>
Für die Verwaltung der Regatta muss die APP Vereinsverwaltung installiert werden.
<a href="https://github.com/kube-csc/vereinsverwaltung" target="_blank"></a>
zum GitHub Projekt Vereinsverwaltung ab V00.10.xx
</p>

<h2>Installation</h2>
<ul>
   <li>https://github.com/kube-csc/regattaView.git</li>
   <li>.env Datei ausfüllen (Es werden auch Informationen über den Verein abgefragt.)</li>
   <li>Composer herunterladen curl -sS https://getcomposer.org/installer</li>
   <li>Installation des Composer</li>
   <li>composer.phar install</li>
   <li>In Ordner "public sind die folgenden Dateien anzulegen:
   <ul>
     <li>apple-touch-icon.png</li>
     <li>favicon.ico</li>
   </ul>
</ul>

<h2>Update</h2>
<ul>
   <li>git pull origin main</li>
</ul>
<h2>Zugehörige Projekte</h2>
<h3>Regatta Management</h3>  
<p>
    Meldeportal für Teilnehmer für Regatten<br>
    Die Version V00.01.XX <a href="https://github.com/kube-csc/regattamanager.git" target="_blank">https://github.com/kube-csc/regattamanager.git</a> 
    ist kompatibel ab der Version V00.08.XX <a href="https://github.com/kube-csc/vereinsverwaltung.git" target="_blank">https://github.com/kube-csc/vereinsverwaltung.git</a>.
</p>
<h3>Kurse</h3>
<p>
Die App bietet ein Kursbuchungssystem, das auch für Fahrten oder Trainings verwendet werden kann. Sie hat folgende Funktionen:<br>
Verwaltung: Sportgeräte, Räume usw. können verwaltet werden.<br>
Teilnehmer: Accounts anlegen und verwalten.<br>
Kursübersicht: Anzeige der verfügbaren Kurse und Termine sowie Informationen zu den Kursen, Trainings, Übungen oder Fahrten, die angeboten werden.<br>
Trainer bzw. Fahrtenleiter: Trainer bzw. Fahrtenleiter können Termine für die Kurse anlegen und bearbeiten.<br>
Sportgeräte und Räume: Zu den Kursen können Sportgeräte, Räume usw. zugeordnet werden.<br>
Buchung: Teilnehmer können Kurse buchen und ihre eigenen Buchungen bearbeiten.<br>
Teilnehmerverwaltung: Hinzufügen und Entfernen von Teilnehmern durch Trainer bzw. Fahrtenleiter.<br>
Die Version V00.02.XX <a href="https://github.com/kube-csc/kurse.git" target="_blank">https://github.com/kube-csc/kurse.git</a> ist kompatibel mit der Version V00.10.XX 
<a href="https://github.com/kube-csc/helferplanung.git" target="_blank">https://github.com/kube-csc/vereinsverwaltung.git</a>.
</p>
<hr>
<h3>Helferlisten</h3>
<p>
Helferliste ist eine APP, die eine Liste von Personen verwaltet, die bereit sind, bei einem Event, einer Veranstaltung oder einem Projekt zu helfen. 
Die Liste enthält die Namen und E-Mail-Adressen der Helfer. Die Helferliste wird von der Person oder Organisation erstellt, die für die Organisation der Veranstaltung verantwortlich ist. Die Liste kann verwendet werden, um 
die Helfer zu koordinieren und sicherzustellen, dass alle Aufgaben abgedeckt sind.<br>
Die Version V00.01.XX <a href="https://github.com/kube-csc/helferplanung.git" target="_blank">https://github.com/kube-csc/helferplanung.git</a> ist kompatibel mit der Version V00.04.XX <a href="https://github.com/kube-csc/helferplanung.git" target="_blank">https://github.com/kube-csc/helferplanung.git</a>.
</p>
<hr>
<br>
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
