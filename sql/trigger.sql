CREATE TRIGGER limita_prestiti_trigger
BEFORE INSERT ON prestiti
FOR EACH ROW
EXECUTE FUNCTION trigger_limita_prestiti();

CREATE TRIGGER trigger_verifica_ritardi
BEFORE INSERT ON prestiti
FOR EACH ROW
EXECUTE FUNCTION verifica_ritardi();

CREATE TRIGGER trigger_aggiorna_viste_catalogo
AFTER INSERT OR UPDATE OR DELETE
ON catalogo
FOR EACH STATEMENT
EXECUTE FUNCTION aggiorna_viste_materializzate();

CREATE TRIGGER trigger_aggiorna_viste_prestiti
AFTER INSERT OR UPDATE OR DELETE
ON prestiti
FOR EACH STATEMENT
EXECUTE FUNCTION aggiorna_viste_materializzate();