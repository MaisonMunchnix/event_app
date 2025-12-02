<?=  view('template/header') ?>

<h1 class="mb-3">Upcoming Events</h1>

<?php if(empty($events)):?>
<p>No events available at the moment.</p>
<?php endif; ?>

<?php foreach($events as $event):?>

<div class="card mb-3">
    <div class="card-body p-4">
        <h5 class="card-title"><?= esc($event['title']) ?></h5>
        <p class="card-text"><?=  esc($event['description']) ?></p>
        <p class="card-text">
            <strong>Venue:</strong> <?= esc($event['venue']) ?> | 
            <strong>Date:</strong> <?= esc($event['event_date']) ?> |
            <strong>Registered:
                <span class="badge bg-success"><?= esc($event['registered_count']) ?></span>
            </strong>
        </p>
        <a href="/events/register/<?= $event['id'] ?>" class="btn btn-primary">Register</a>
    </div>
</div>

<?php endforeach;?>

<?=  view('template/footer') ?>