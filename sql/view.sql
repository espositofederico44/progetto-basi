CREATE MATERIALIZED VIEW vista_sede_statistiche AS
WITH catalogo_statistiche AS (
    -- Conteggi dalla tabella catalogo
    SELECT 
        sede, 
        COUNT(isbn) AS numero_totale_copie_gestite, 
        COUNT(DISTINCT isbn) AS numero_totale_isbn_gestiti
    FROM catalogo
    GROUP BY sede
),
prestiti_statistiche AS (
    -- Conteggi dalla tabella prestiti associati alla sede tramite catalogo
    SELECT 
        c.sede,
        COUNT(CASE WHEN p.data_riconsegna IS NULL THEN 1 END) AS numero_prestiti_in_corso,
        COUNT(CASE WHEN p.data_riconsegna IS NOT NULL THEN 1 END) AS numero_prestiti_conclusi,
        COUNT(p.id) AS numero_totale_prestiti
    FROM 
        prestiti p
    INNER JOIN 
        catalogo c ON c.id = p.id_catalogo  -- Uniamo i prestiti alla sede tramite catalogo
    GROUP BY 
        c.sede
)
-- Unione dei risultati
SELECT 
    c.sede,
    c.numero_totale_copie_gestite,
    c.numero_totale_isbn_gestiti,
    COALESCE(p.numero_prestiti_in_corso, 0) AS numero_prestiti_in_corso,
    COALESCE(p.numero_prestiti_conclusi, 0) AS numero_prestiti_conclusi,
    COALESCE(p.numero_totale_prestiti, 0) AS numero_totale_prestiti
FROM 
    catalogo_statistiche c
LEFT JOIN 
    prestiti_statistiche p ON c.sede = p.sede;

   
CREATE MATERIALIZED VIEW vista_libri_ritardo AS
WITH libri_ritardo AS (
    SELECT
        p.id AS id_prestito,
        p.cf_lettore,
        l.isbn,
        l.titolo,
        p.data_inizio,
        p.data_fine,
        c.sede
    FROM
        prestiti p
    INNER JOIN
        catalogo c ON p.id_catalogo = c.id
    INNER JOIN
        libri l ON c.isbn = l.isbn
    WHERE
        p.data_riconsegna IS NULL
        AND p.data_fine < CURRENT_DATE
)
SELECT
    lr.id_prestito,
    lr.isbn,
    lr.titolo,
    lr.data_inizio,
    lr.data_fine,
    lr.cf_lettore,
    lr.sede
FROM
    libri_ritardo lr;