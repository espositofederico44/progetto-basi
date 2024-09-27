CREATE OR REPLACE FUNCTION check_max_prestiti(cf_lett VARCHAR(16)) 
RETURNS BOOLEAN AS $$
DECLARE
    categoria tipo_lettore;
    prestiti_correnti INTEGER;
    max_prestiti INTEGER;
BEGIN
    -- Recupera la categoria del lettore
    SELECT l.categoria INTO categoria
    FROM lettore l
    WHERE l.cf = cf_lett;

    -- Conta i prestiti correnti (non restituiti)
    SELECT COUNT(*) INTO prestiti_correnti
    FROM prestiti p
    WHERE p.cf_lettore = cf_lett AND p.data_riconsegna IS NULL;

    -- Definisce il numero massimo di prestiti in base alla categoria
    IF categoria = 'base' THEN
        max_prestiti := 3;
    ELSIF categoria = 'premium' THEN
        max_prestiti := 5;
    ELSE
        RAISE EXCEPTION 'Categoria non valida';
    END IF;

    -- Verifica se il numero massimo di prestiti è stato raggiunto
    IF prestiti_correnti >= max_prestiti THEN
        RETURN FALSE;
    ELSE
        RETURN TRUE;
    END IF;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION trigger_limita_prestiti()
RETURNS TRIGGER AS $$
BEGIN
    -- Verifica il numero massimo di prestiti prima di inserire un nuovo prestito
    IF NOT check_max_prestiti(NEW.cf_lettore) THEN
        RAISE NOTICE 'Numero massimo di prestiti raggiunto per questo lettore: %', NEW.cf_lettore;
        RETURN NULL;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fetch_books(_limit integer, _offset integer) 
RETURNS TABLE(
    isbn varchar(13),
    titolo varchar(255),
    trama text
    )
LANGUAGE plpgsql
AS $$
BEGIN    
    RETURN QUERY
    SELECT L.isbn, L.titolo, L.trama
    FROM libri AS L
    ORDER BY L.isbn
    LIMIT _limit OFFSET _offset;
END;
$$;

CREATE OR REPLACE FUNCTION fetch_book_by_isbn(_isbn varchar)
RETURNS TABLE(
    isbn varchar(13),
    titolo varchar(255),
    trama text
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT l.isbn, l.titolo, l.trama
    FROM libri AS l
    WHERE l.isbn = _isbn;
END;
$$;


CREATE OR REPLACE FUNCTION fetch_authors(_limit integer, _offset integer)
RETURNS TABLE(
    id integer,
    nome varchar,
    cognome varchar,
    biografia text,
    data_nascita date,
    data_morte date
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT a.id, a.nome, a.cognome, a.biografia, a.data_nascita, a.data_morte
    FROM autori AS a
    ORDER BY a.id
    LIMIT _limit OFFSET _offset;
END;
$$;


CREATE OR REPLACE FUNCTION fetch_author_by_id(p_id integer)
RETURNS TABLE(
    id integer,
    nome varchar,
    cognome varchar,
    biografia text,
    data_nascita date,
    data_morte date
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT a.id, a.nome, a.cognome, a.biografia, a.data_nascita, a.data_morte
    FROM autori AS a
    WHERE a.id = p_id;
END;
$$;


CREATE OR REPLACE FUNCTION search_comune(p_query varchar)
RETURNS TABLE(
    id integer,
    nome varchar
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT c.id, c.nome
    FROM comuni AS c
    WHERE c.nome ILIKE p_query || '%'
    LIMIT 10;
END;
$$;


CREATE OR REPLACE FUNCTION get_comune_id_by_name(p_nome varchar)
RETURNS integer
LANGUAGE plpgsql
AS $$
DECLARE
    comune_id integer;
BEGIN
    -- Esegue la query per ottenere l'ID del comune
    SELECT c.id INTO comune_id
    FROM comuni AS c
    WHERE c.nome = p_nome
    LIMIT 1;

    -- Restituisce l'ID del comune
    RETURN comune_id;
END;
$$;


CREATE OR REPLACE FUNCTION fetch_branches()
RETURNS TABLE(
    id integer,
    nome VARCHAR(100),
    indirizzo VARCHAR(255),
    cap VARCHAR(5),
    comune integer
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT s.id, s.nome, s.indirizzo, s.cap, s.comune
    FROM sedi AS s
    ORDER BY s.id;
END;
$$;


CREATE OR REPLACE FUNCTION fetch_branch_by_id(p_id integer)
RETURNS TABLE(
    id integer,
    nome varchar,
    indirizzo varchar,
    cap varchar(5),
    comune_id integer,
    comune_nome varchar
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT s.id, s.nome, s.indirizzo, s.cap, s.comune, c.nome AS comune_nome
    FROM sedi AS s
    JOIN comuni AS c ON s.comune = c.id
    WHERE s.id = p_id;
END;
$$;


CREATE OR REPLACE FUNCTION available_branches(p_cf_bibliotecario varchar)
RETURNS TABLE(
    id integer,
    nome VARCHAR(100),
    cf VARCHAR(16)
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT s.id, s.nome, b.cf
    FROM sedi AS s
    LEFT JOIN bibliotecario AS b
    ON s.id = b.sede AND b.cf = p_cf_bibliotecario;
END;
$$;


CREATE OR REPLACE FUNCTION get_branch_by_cf(_cf varchar) 
RETURNS TABLE(
    id integer,
    nome varchar(100)
) 
LANGUAGE plpgsql
AS $$
BEGIN    
    RETURN QUERY
    SELECT s.id, s.nome
    FROM bibliotecario b
    JOIN sedi s ON b.sede = s.id
    WHERE b.cf = _cf;
END;
$$;


CREATE OR REPLACE FUNCTION show_catalog_by_cf(_cf varchar)
RETURNS TABLE (
    titolo varchar(255),
    isbn varchar(13),
    num_copie integer,
    disponibili integer
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT l.titolo, 
           c.isbn, 
           COUNT(*)::integer as num_copie,  -- Numero di copie totale
           (COUNT(*) - COALESCE(
               (SELECT COUNT(*) 
                FROM prestiti p 
                JOIN catalogo c2 ON p.id_catalogo = c2.id
                WHERE c2.isbn = c.isbn
                AND p.data_riconsegna IS NULL), 0))::integer as disponibili  -- Copie disponibili
    FROM catalogo c
    JOIN sedi s ON c.sede = s.id
    JOIN libri l ON c.isbn = l.isbn
    JOIN bibliotecario b ON s.id = b.sede
    WHERE b.cf = _cf
    GROUP BY c.isbn, l.titolo;
END;
$$;


CREATE OR REPLACE FUNCTION search_books_by_title(_title text)
RETURNS TABLE (
    isbn varchar(13),
    titolo varchar(255)
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT l.isbn, l.titolo::VARCHAR(255)
    FROM libri l
    WHERE l.titolo ILIKE '%' || _title || '%';
END;
$$;


CREATE OR REPLACE FUNCTION get_available_branches_by_isbn(_isbn varchar(13))
RETURNS TABLE (
    id integer,
    nome varchar(50),
    indirizzo varchar(255)
) 
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT c.id, s.nome, s.indirizzo
    FROM catalogo c
    JOIN sedi s ON c.sede = s.id
    WHERE c.isbn = _isbn;
END;
$$;


CREATE OR REPLACE FUNCTION is_book_available(_id_catalogo integer)
RETURNS boolean
LANGUAGE plpgsql
AS $$
DECLARE
    prestiti_attivi integer;
BEGIN
    SELECT COUNT(*)
    INTO prestiti_attivi
    FROM prestiti p
    WHERE p.id_catalogo = _id_catalogo
      AND p.data_riconsegna IS NULL;

       IF prestiti_attivi > 0 THEN
        RETURN false;
    ELSE
        RETURN true;
    END IF;
END;
$$;


CREATE OR REPLACE FUNCTION get_active_loans(_cf_lettore varchar(16))
RETURNS TABLE (
    isbn varchar(13),
    titolo varchar(255),
    data_inizio date,
    data_fine date
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT c.isbn, l.titolo, p.data_inizio, p.data_fine
    FROM prestiti p
    JOIN catalogo c ON p.id_catalogo = c.id
    JOIN libri l ON c.isbn = l.isbn
    WHERE p.cf_lettore = _cf_lettore
      AND p.data_riconsegna IS NULL;
END;
$$;


CREATE OR REPLACE FUNCTION get_returned_loans(_cf_lettore varchar(16))
RETURNS TABLE (
    isbn varchar(13),
    titolo varchar(255),
    data_inizio date,
    data_fine date,
    data_riconsegna date
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT c.isbn, l.titolo, p.data_inizio, p.data_fine, p.data_riconsegna
    FROM prestiti p
    JOIN catalogo c ON p.id_catalogo = c.id
    JOIN libri l ON c.isbn = l.isbn
    WHERE p.cf_lettore = _cf_lettore
      AND p.data_riconsegna IS NOT NULL;
END;
$$;


CREATE OR REPLACE FUNCTION get_prestiti_per_sede(_cf_bibliotecario varchar(16))
RETURNS TABLE (
    id integer,
    isbn varchar(13),
    titolo varchar(255),
    cf_lettore varchar(16),
    data_inizio date,
    data_fine date,
    data_riconsegna date
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT p.id, c.isbn, l.titolo, p.cf_lettore, p.data_inizio, p.data_fine, p.data_riconsegna
    FROM prestiti p
    JOIN catalogo c ON p.id_catalogo = c.id
    JOIN libri l ON c.isbn = l.isbn
    JOIN bibliotecario b ON b.sede = c.sede
    WHERE b.cf = _cf_bibliotecario;
END;
$$;

CREATE OR REPLACE FUNCTION verifica_ritardi()
RETURNS TRIGGER AS $$
BEGIN
    -- Controlla il numero di ritardi del lettore
    IF (SELECT ritardi FROM lettore WHERE cf = NEW.cf_lettore) > 4 THEN
       RAISE NOTICE 'Il lettore con codice fiscale % ha più di 5 ritardi attivi.', NEW.cf_lettore;
       RETURN NULL;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;



CREATE OR REPLACE FUNCTION aggiorna_viste_materializzate()
RETURNS TRIGGER AS $$
BEGIN
    REFRESH MATERIALIZED VIEW vista_sede_statistiche;
    REFRESH MATERIALIZED VIEW vista_libri_ritardo;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION get_lettori_con_ritardi()
RETURNS TABLE(cf VARCHAR, nome VARCHAR, cognome VARCHAR, ritardi INTEGER)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT u.cf, u.nome, u.cognome, l.ritardi
    FROM lettore l
    JOIN utenti u ON l.cf = u.cf
    WHERE l.ritardi > 0;
END;
$$;

CREATE OR REPLACE FUNCTION get_scritto()
RETURNS TABLE(
    id_autore INTEGER,
    isbn VARCHAR(13),
    titolo VARCHAR(255),
    nome VARCHAR(50),
    cognome VARCHAR(50)
) 
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT a.id AS id_autore, l.isbn, l.titolo, a.nome, a.cognome
    FROM scritto s
    JOIN autori a ON s.id_autore = a.id
    JOIN libri l ON s.isbn_libro = l.isbn
    ORDER BY l.isbn;
END;
$$;

CREATE OR REPLACE FUNCTION get_catalog_by_id_sede(p_sede_id integer)
RETURNS TABLE(
    catalogo_id integer,
    isbn varchar(13),
    titolo varchar(255),
    disponibile boolean
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        c.id AS catalogo_id,
        l.isbn, 
        l.titolo, 
        -- Verifica se non ci sono prestiti attivi per quel catalogo (prestiti con data_riconsegna NULL)
        CASE WHEN NOT EXISTS (
            SELECT 1 
            FROM prestiti p 
            WHERE p.id_catalogo = c.id 
            AND p.data_riconsegna IS NULL
        ) THEN true ELSE false END AS disponibile
    FROM catalogo c
    JOIN libri l ON c.isbn = l.isbn
    WHERE c.sede = p_sede_id;
END;
$$;

CREATE OR REPLACE FUNCTION get_all_sedi()
RETURNS TABLE(
    sede_id integer,
    sede_nome varchar(100)
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT s.id AS sede_id, s.nome AS sede_nome
    FROM sedi s
    ORDER BY s.nome;
END;
$$;


CREATE OR REPLACE FUNCTION get_sede_by_id(p_sede_id integer)
RETURNS TABLE(
    sede_id integer,
    sede_nome varchar(100)
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT s.id AS sede_id, s.nome AS sede_nome
    FROM sedi s
    WHERE s.id = p_sede_id;
END;
$$;

CREATE OR REPLACE FUNCTION get_catalogo_non_raggruppato(p_sede_id INTEGER)
RETURNS TABLE (
    catalogo_id INTEGER,
    isbn VARCHAR(13),
    titolo VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT c.id AS catalogo_id, l.isbn, l.titolo
    FROM catalogo c
    JOIN libri l ON c.isbn = l.isbn
    WHERE c.sede = p_sede_id;
END;
$$;