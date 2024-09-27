CREATE type tipo_utenti AS ENUM (
    'admin',
    'bibliotecario',
    'lettore'
);

CREATE type tipo_lettore AS ENUM (
    'base',
    'premium'
);

CREATE TABLE utenti (
    cf VARCHAR(16) PRIMARY KEY,
    cognome VARCHAR(50) NOT NULL,
    nome VARCHAR(50) NOT NULL,
    tipo tipo_utenti NOT NULL,
    password VARCHAR(64) NOT null,
    CONSTRAINT utenti_cognome_check CHECK ((cognome ~* '^.+$'::text)),
    CONSTRAINT utenti_nome_check CHECK ((nome ~* '^.+$'::text)),
    CHECK (cf ~ '^[A-Z]{6}[0-9]{2}[A-Z][0-9]{2}[A-Z][0-9]{3}[A-Z]$')
);


CREATE TABLE bibliotecario (
    cf VARCHAR(16) PRIMARY KEY,
    sede INTEGER,
    FOREIGN KEY (cf) REFERENCES utenti(cf),
    FOREIGN KEY (sede) REFERENCES sedi(id)
);

CREATE TABLE lettore (
    cf VARCHAR(16) PRIMARY KEY,
    categoria tipo_lettore NOT NULL,
    ritardi INTEGER DEFAULT 0,
    FOREIGN KEY (cf) REFERENCES utenti(cf)
);

CREATE TABLE admin (
    cf VARCHAR(16) PRIMARY KEY,
    FOREIGN KEY (cf) REFERENCES utenti(cf)
);

CREATE TABLE province (
    sigla VARCHAR(2) PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

CREATE TABLE autori (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    biografia TEXT,
    data_nascita DATE NOT NULL,
    data_morte DATE
);


CREATE TABLE libri (
    isbn VARCHAR(13) PRIMARY KEY,
    titolo VARCHAR(255) NOT NULL,
    trama TEXT
);

CREATE TABLE catalogo (
    id SERIAL PRIMARY KEY,
    sede INTEGER NOT NULL,
    isbn VARCHAR(13) NOT NULL,
    CONSTRAINT fk_sede FOREIGN KEY (sede) REFERENCES sedi(id)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT fk_isbn FOREIGN KEY (isbn) REFERENCES libri(isbn)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
);


CREATE TABLE scritto (
    id_autore INTEGER NOT NULL,
    isbn_libro VARCHAR(13) NOT NULL,
    PRIMARY KEY (id_autore, isbn_libro),
    CONSTRAINT fk_id_autore FOREIGN KEY (id_autore) REFERENCES autori(id)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    CONSTRAINT fk_isbn_libro FOREIGN KEY (isbn_libro) REFERENCES libri(isbn)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
);


CREATE TABLE comuni (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    provincia VARCHAR(2),
    FOREIGN KEY (provincia) REFERENCES province(sigla)
);


CREATE TABLE prestiti (
    id SERIAL PRIMARY KEY,
    cf_lettore VARCHAR(16) NOT NULL,
    id_catalogo INTEGER NOT NULL,
    data_inizio DATE NOT NULL,
    data_fine DATE NOT NULL,
    data_riconsegna DATE,
    FOREIGN KEY (cf_lettore) REFERENCES lettore(cf)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    FOREIGN KEY (id_catalogo) REFERENCES catalogo(id)
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    UNIQUE (id_catalogo, data_riconsegna)
);



CREATE TABLE sedi (
	id serial NOT NULL,
	nome varchar(100) NOT NULL,
	indirizzo varchar(255) NOT NULL,
	cap varchar(5) NULL,
	comune int4 NOT NULL,
	CONSTRAINT chk_cap CHECK ((cap ~ '^\d{5}$'::text)),
	CONSTRAINT sedi_pkey PRIMARY KEY (id),
	CONSTRAINT fk_comune FOREIGN KEY (comune) REFERENCES comuni(id)
);