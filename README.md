# uia_motell

# php_booking-prosjekt

Room Booking System - README
Dette er et system for å booke rom på et lite motell med 25 rom til sammen.

Prosjektbeskrivelse:
RoomBookingSystem er en dynamisk nettside som gir brukere muligheten til å booke rom på et motell med forskjellige romtyper. Dette systemet er bygget med PHP og bruker Bootstrap som front-end rammeverk for brukergrensesnittet. Hovedmålet er å gi både gjester og administratorer funksjonalitet for å håndtere romreservasjoner effektivt.

Funksjonaliteter:
Romtyper og Kapasitet:

Systemet har minst tre forskjellige romtyper (enkeltrom, dobbeltrom, junior suite).
Hver romtype har en begrensning på antall personer (voksne og barn) som kan bo der.
Gjesteregistrering og Administratorregistrering:

Gjestene kan registrere seg for å booke rom.
Administratorer kan også registrere seg for å håndtere rom og bookingdata.
Romadministrasjon (Kun for Administratorer):

Administratorer kan navngi og beskrive romtyper.
Administratorer kan gjøre enkelte rom utilgjengelige i bestemte perioder (for vedlikehold eller spesielle arrangementer).
Rombooking for Gjestene:

Gjestene kan søke etter tilgjengelige rom basert på innsjekk- og utsjekksdatoer.
Gjestene kan spesifisere antall voksne og barn som skal bo i rommene.
Valgfrie preferanser som etasjenivå eller om rommet skal være i nærheten av heis kan inkluderes.
Lojalitetsprogram og Gjesteprofil:

Et lojalitetsprogram som lar gjester se overnattingshistorikk, hente ut kvitteringer, og lagre preferanser kan implementeres.
Brukerroller:
Gjester:
Kan registrere seg og logge inn for å booke rom.
Kan søke etter tilgjengelige rom basert på dato og andre preferanser.
Administratorer:
Kan logge inn på et eget grensesnitt for å administrere rom, gjøre rom utilgjengelige, og oppdatere informasjon om romtyper.
Teknologier:
Backend: PHP 8.2
Frontend: HTML5, CSS3 (Bootstrap), JavaScript (jQuery)
Database: MySQL eller en annen kompatibel SQL-database for å håndtere bookingdata og brukerregistrering.
Krav for å kjøre prosjektet lokalt:
Server:
Apache (med mod_php aktivert) eller en annen server som støtter PHP.
PHP:
Minimum PHP versjon 8.0 eller høyere.
Database:
MySQL eller MariaDB for å lagre bruker- og bookingdata.



# Room Booking System

```

UIA_MOTELL/
├── admin/
│   ├── home.php
│   ├── rooms.php
│   ├── settings.php
├── config/
│   ├── config.php
│   ├── db.php
├── inc/
│   ├── contact.php
│   ├── home.php
│   ├── login_register.php
│   ├── navbar.php
│   ├── rooms.php
├── migrations/
│   ├── run-mig.php
│   ├── run-seed.php
├── public/
│   ├── images/
│   │   ├── home/
│   │   ├── reviews/
│   │   ├── rooms/
│   ├── index.php
│   ├── scripts.js
│   ├── styles.css
├── README.md



