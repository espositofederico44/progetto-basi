<div class="mt-5 container">
  <h1 class="mb-4 text-primary">Collega autore libro</h1>
  <form method="POST" action="">
    <div class="row">
      <div class="col-12 col-md-6 mb-4">
        <h2 class="text-primary">Seleziona un libro</h2>
        <?php foreach ($books as $book): ?>
          <label>
            <input class="form-check-input" type="radio" name="isbn_libro" value="<?= $book['isbn']; ?>"> <?= $book['titolo']; ?> (ISBN: <?= $book['isbn']; ?>)
          </label><br>
        <?php endforeach; ?>

        <nav class="mt-5" aria-label="Page navigation for books">
          <ul class="pagination justify-content-center">
            <?php if ($currentPageBooks > 1): ?>
              <li class="page-item">
                <a class="page-link" href="?page_books=<?= $currentPageBooks - 1; ?>&page_authors=<?= $currentPageAuthors; ?>" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPagesBooks; $i++): ?>
              <li class="page-item <?= $currentPageBooks === $i ? 'active' : '' ?>">
                <a class="page-link" href="?page_books=<?= $i; ?>&page_authors=<?= $currentPageAuthors; ?>"><?= $i; ?></a>
              </li>
            <?php endfor; ?>

            <?php if ($currentPageBooks < $totalPagesBooks): ?>
              <li class="page-item">
                <a class="page-link" href="?page_books=<?= $currentPageBooks + 1; ?>&page_authors=<?= $currentPageAuthors; ?>" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>

      <div class="col-12 col-md-6 mb-4">
        <h2 class="text-primary">Seleziona un autore</h2>
        <?php foreach ($authors as $author): ?>
          <label>
            <input class="form-check-input" type="radio" name="id_autore" value="<?= $author['id']; ?>"> <?= $author['nome']; ?> <?= $author['cognome']; ?>
          </label><br>
        <?php endforeach; ?>

        <nav class="mt-5" aria-label="Page navigation for authors">
          <ul class="pagination justify-content-center">
            <?php if ($currentPageAuthors > 1): ?>
              <li class="page-item">
                <a class="page-link" href="?page_books=<?= $currentPageBooks; ?>&page_authors=<?= $currentPageAuthors - 1; ?>" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPagesAuthors; $i++): ?>
              <li class="page-item <?= $currentPageAuthors === $i ? 'active' : '' ?>">
                <a class="page-link" href="?page_books=<?= $currentPageBooks; ?>&page_authors=<?= $i; ?>"><?= $i; ?></a>
              </li>
            <?php endfor; ?>

            <?php if ($currentPageAuthors < $totalPagesAuthors): ?>
              <li class="page-item">
                <a class="page-link" href="?page_books=<?= $currentPageBooks; ?>&page_authors=<?= $currentPageAuthors + 1; ?>" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
      <button type="submit" name="register" class="btn btn-primary">Collega Autore a Libro</button>
    </div>
  </form>
  <?php if ($message) echo "<div class='alert alert-info' role='alert'><p>$message</p></div>"; ?>
  <?php include '../components/back.php'; ?>
</div>