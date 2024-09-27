# MANUALE INSTALLAZIONE PROGETTO BASI DI DATI - INFORMATICA

> Federico Esposito - 18 Settembre 2024

<p align="center">
Manuale utente per il progetto "Piattaforma per la gestione di una biblioteca" per il corso "Basi di dati (informatica)" (a.a. 2023-2024, appello di Settembre).
Realizzato da Federico Esposito (987540).
</p>
<p>Lo sviluppo del progetto è documentato in un apposito repository di Github al seguente <a href=https://github.com/espositofederico44/progetto-basi>link</a></p>

## Informazioni utili

- Versione di XAMPP 3.3.0 (Apache 2.4.58 - PHP Version 8.2.12). Per usare PostgreSQL su xampp andare nel file php.ini e attivare le estensioni relativi a postgre togliendo il punto e virgola prima del inizio della frase

- Versione di PostgreSQL 16.3-2

- Il database è sotto il folder dump

- La relazione e il manuale utente è sotto docs, nel formato markdown oppure in pdf

- Nel file conf/config.php vanno inserite le impostazioni per accedere al db

  - **DB_HOST** (es. localhost)
  - **DB_PORT** (porta di postgre es. 5432)
  - **DB_NAME** (nome del db)
  - **DB_USERNAME** (utente del db)
  - **DB_PASSWORD** (password utente del db)

- Credenziali degli utenti
  1. **Admin** | CF: RSSMRA80L05F593A | password: password
  - Gestione delle sedi, libri, autori
  2. **Bibliotecario** | CF: RSSMRA81L05F593A | password: password
  - Gestione dei libri, autori, catalogo, prestiti, statistiche, lettori
  3. **Lettore** | CF: RSSMRA82L05F593A | password: password
  - Richiedi prestiti, visualizza i cataloghi

Si può consultare la tabella utenti per vedere gli altri utenti, la password è uguale per tutti.
