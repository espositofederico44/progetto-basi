CREATE OR REPLACE PROCEDURE add_book(_isbn varchar, _titolo varchar, _trama text)
LANGUAGE plpgsql
AS $$
begin
    INSERT INTO libri (isbn, titolo, trama)
    VALUES (_isbn, _titolo, _trama);
END;
$$;

CREATE OR REPLACE PROCEDURE delete_book_by_isbn(_isbn varchar)
LANGUAGE plpgsql
AS $$
BEGIN
    DELETE FROM libri
    WHERE isbn = _isbn;
END;
$$;

CREATE OR REPLACE PROCEDURE add_author(
    p_nome varchar,
    p_cognome varchar,
    p_biografia text,
    p_data_nascita date,
    p_data_morte date
)
LANGUAGE plpgsql
AS $$
BEGIN
    INSERT INTO autori (nome, cognome, biografia, data_nascita, data_morte)
    VALUES (p_nome, p_cognome, p_biografia, p_data_nascita, p_data_morte);
END;
$$;

CREATE OR REPLACE PROCEDURE delete_author_by_id(p_id integer)
LANGUAGE plpgsql
AS $$
BEGIN
    DELETE FROM autori
    WHERE id = p_id;
END;
$$;

CREATE OR REPLACE PROCEDURE update_author_by_id(
    p_id integer,
    p_nome varchar,
    p_cognome varchar,
    p_biografia text,
    p_data_nascita date,
    p_data_morte date
)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE autori
    SET nome = p_nome,               -- Aggiorna il nome dell'autore.
        cognome = p_cognome,         -- Aggiorna il cognome.
        biografia = p_biografia,     -- Aggiorna la biografia (può essere NULL).
        data_nascita = p_data_nascita, -- Aggiorna la data di nascita.
        data_morte = p_data_morte    -- Aggiorna la data di morte (può essere NULL).
    WHERE id = p_id;                 -- L'autore è identificato dal suo ID.
END;
$$;

CREATE OR REPLACE PROCEDURE update_book_by_isbn(
    p_isbn varchar, 
    p_titolo varchar, 
    p_trama text
)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE libri
    SET titolo = p_titolo,
        trama = p_trama
    WHERE isbn = p_isbn;
END;
$$;


CREATE OR REPLACE PROCEDURE add_branch(
    p_nome varchar,
    p_indirizzo varchar,
    p_cap varchar,
    p_comune_id integer
)
LANGUAGE plpgsql
AS $$
DECLARE
    new_branch_id integer;
BEGIN
    INSERT INTO sedi (nome, indirizzo, cap, comune)
    VALUES (p_nome, p_indirizzo, p_cap, p_comune_id)
    RETURNING id INTO new_branch_id;

    RETURN new_branch_id;
END;
$$;


CREATE OR REPLACE PROCEDURE update_branch(
    p_id integer,
    p_nome varchar,
    p_indirizzo varchar,
    p_cap varchar(5),
    p_comune integer
)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE sedi
    SET nome = p_nome,
        indirizzo = p_indirizzo,
        cap = p_cap,
        comune = p_comune
    WHERE id = p_id;
END;
$$;

CREATE OR REPLACE PROCEDURE delete_branch(p_id integer)
LANGUAGE plpgsql
AS $$
BEGIN
    DELETE FROM sedi
    WHERE id = p_id;
END;
$$;

CREATE OR REPLACE PROCEDURE link_user_branches(
    p_cf_bibliotecario varchar,
    p_sede_id integer
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Verifica se il bibliotecario è già assegnato a una sede
    IF EXISTS (SELECT 1 FROM bibliotecario WHERE cf = p_cf_bibliotecario) THEN
        -- Aggiorna la sede del bibliotecario
        UPDATE bibliotecario
        SET sede = p_sede_id
        WHERE cf = p_cf_bibliotecario;
    ELSE
        -- Inserisce un nuovo record per il bibliotecario con la nuova sede
        INSERT INTO bibliotecario (cf, sede)
        VALUES (p_cf_bibliotecario, p_sede_id);
    END IF;
END;
$$;


CREATE OR REPLACE PROCEDURE collega_autore_libro(_id_autore integer, _isbn_libro varchar)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Verifica se l'associazione esiste già
    IF EXISTS (SELECT 1 FROM scritto WHERE id_autore = _id_autore AND isbn_libro = _isbn_libro) THEN
        RAISE NOTICE 'Autore % è già collegato al libro con ISBN %', _id_autore, _isbn_libro;
    ELSE
        -- Inserisce l'associazione tra autore e libro
        INSERT INTO scritto (id_autore, isbn_libro) VALUES (_id_autore, _isbn_libro);
        RAISE NOTICE 'Autore % collegato al libro con ISBN %', _id_autore, _isbn_libro;
    END IF;
END;
$$;

CREATE OR REPLACE PROCEDURE controlla_ritardo_e_aggiorna(
    p_id_prestito INTEGER
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_cf_lettore VARCHAR(16);
    v_data_fine DATE;
    v_data_riconsegna DATE;
BEGIN
    -- Ottiene le informazioni del prestito
    SELECT p.cf_lettore, p.data_fine, p.data_riconsegna
    INTO v_cf_lettore, v_data_fine, v_data_riconsegna
    FROM prestiti p
    WHERE p.id = p_id_prestito;

    -- Verifica se il libro è stato restituito in ritardo
    IF v_data_riconsegna > v_data_fine THEN
        -- Aggiorna il contatore dei ritardi del lettore
        UPDATE lettore
        SET ritardi = ritardi + 1
        WHERE cf = v_cf_lettore;
    END IF;
END;
$$;

CREATE OR REPLACE PROCEDURE add_book_to_branch(
    _sede_id integer, 
    _isbn varchar
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Inserimento del libro nella tabella 'catalogo'
    INSERT INTO catalogo (sede, isbn) 
    VALUES (_sede_id, _isbn);
END;
$$;

CREATE OR REPLACE PROCEDURE add_loan(
    _cf_lettore varchar(16), 
    _id_catalogo integer, 
    _data_inizio date, 
    _data_fine date
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Verifica se il libro è disponibile
    IF (SELECT is_book_available(_id_catalogo)) THEN
        -- Inserisce il prestito nella tabella 'prestiti'
        INSERT INTO prestiti (cf_lettore, id_catalogo, data_inizio, data_fine)
        VALUES (_cf_lettore, _id_catalogo, _data_inizio, _data_fine);
    ELSE
        -- Se il libro non è disponibile, solleva un'eccezione
        RAISE EXCEPTION 'Il libro non è disponibile per il prestito.';
    END IF;
END;
$$;

CREATE OR REPLACE PROCEDURE azzera_ritardi_lettore(_cf VARCHAR)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Resetta i ritardi del lettore specificato
    UPDATE lettore
    SET ritardi = 0
    WHERE cf = _cf;

    -- Conferma l'operazione
    RAISE NOTICE 'Ritardi azzerati per il lettore %', _cf;
END;
$$;

CREATE OR REPLACE PROCEDURE elimina_scritto(_id_autore INTEGER, _isbn_libro VARCHAR(13))
LANGUAGE plpgsql
AS $$
BEGIN
    DELETE FROM scritto
    WHERE id_autore = _id_autore AND isbn_libro = _isbn_libro;
    RAISE NOTICE 'Riga eliminata per autore ID: %, ISBN: %', _id_autore, _isbn_libro;
END;
$$;

CREATE OR REPLACE PROCEDURE proroga_prestito(id_prestito INTEGER)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE prestiti
    SET data_fine = data_fine + INTERVAL '1 month'
    WHERE id = id_prestito AND data_riconsegna IS NULL;
END;
$$;

CREATE OR REPLACE PROCEDURE riconsegna_prestito(
    p_id_prestito INTEGER, 
    p_data_riconsegna DATE
)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE prestiti
    SET data_riconsegna = p_data_riconsegna
    WHERE id = p_id_prestito AND data_riconsegna IS NULL;
END;
$$;


CREATE OR REPLACE PROCEDURE delete_catalogo_entry(p_catalogo_id INTEGER)
LANGUAGE plpgsql
AS $$
BEGIN
    DELETE FROM catalogo WHERE id = p_catalogo_id;
END;
$$;
