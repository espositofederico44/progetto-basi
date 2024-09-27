<!-- footer.php -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<footer class="py-3 my-4">
  <div class="container">
    <ul class="nav justify-content-center border-top p-3 mb-3">
      <li class="nav-item"><a href="https://github.com/espositofederico44/progetto-basi/" class="nav-link px-2 text-body-secondary" target="_blank">Codice sorgente</a></li>
      <li class="nav-item"><a href="#" data-bs-toggle="modal" data-bs-target="#privacyPolicyModal" class="nav-link px-2 text-body-secondary">Privacy Policy</a></li>
      <li class="nav-item"><a href="#" data-bs-toggle="modal" data-bs-target="#contactAdminModal" class="nav-link px-2 text-body-secondary">Contatta un ammisitratore</a></li>
    </ul>

    <div class="modal fade" id="privacyPolicyModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Privacy Policy</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <strong>1. Gestione dei Dati degli Utenti:</strong> Tutti i dati presenti sulla piattaforma sono esclusivamente fittizi e non corrispondono né sono riferibili a dati di persone reali. La finalità del presente sito web è unicamente didattica, nel contesto di un progetto scolastico. Non perseguiremo alcun fine di lucro, marketing o tracciamento degli utenti attraverso questa webapp.
            <br><br>
            <strong>2. Codice Sorgente:</strong> Il codice sorgente di questa webapp è open source e disponibile al pubblico sotto la licenza MIT. Per consultare e contribuire al progetto, è possibile visitare il nostro repository su GitHub.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="contactAdminModal" tabindex="-1" aria-labelledby="contactAdminLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="contactAdminLabel">Contatta un ammisitratore</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Per qualsiasi domanda o problema, puoi contattare un amministratore o invia una mail direttamente all'indirizzo <a href="mailto:admin@example.com">admin@example.com</a>.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
          </div>
        </div>
      </div>
    </div>
</footer>