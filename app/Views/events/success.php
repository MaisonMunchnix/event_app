<?= view('template/header') ?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card mb-4">
      <div class="card-header bg-success text-white">
        <h4 class="mb-0">Registration Successful</h4>
      </div>
      <div class="card-body">
        <p class="lead">
          Thank you, <strong><?= esc($registration['name']) ?></strong>!
        </p>
        <p>
          You have successfully registered for:
        </p>
        <ul class="list-unstyled">
          <li><strong>Event:</strong> <?= esc($event['title'] ?? 'N/A') ?></li>
          <li><strong>Venue:</strong> <?= esc($event['venue'] ?? 'N/A') ?></li>
          <li><strong>Date:</strong> <?= esc($event['event_date'] ?? 'N/A') ?></li>
        </ul>

        <hr>

        <p class="mb-1"><strong>Your details</strong></p>
        <ul class="list-unstyled">
          <li><strong>Name:</strong> <?= esc($registration['name']) ?></li>
          <li><strong>Email:</strong> <?= esc($registration['email']) ?></li>
          <li><strong>Status:</strong> <?= esc($registration['status']) ?></li>
        </ul>

        <a href="/" class="btn btn-primary mt-3">Back to Event List</a>
      </div>
    </div>
  </div>
</div>

<?= view('template/footer') ?>