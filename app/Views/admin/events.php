<?= view('template/header') ?>

<h1 class="mb-3">Manage Events</h1>

<a href="/admin/events/create" class="my-4 btn btn-success">Add New Event</a>

<table class="table table-bordered">
    <tr class="table-dark">
        <th>ID</th>
        <th>Title</th>
        <th>Venue</th>
        <th>Date</th>
        <th>Registrations</th>
        <th>Action</th>
    </tr>

    <?php foreach($events as $event):?>
        <tr>
            <td><?= esc($event['id']) ?></td>
            <td><?= esc($event['title']) ?></td>
            <td><?= esc($event['venue']) ?></td>
            <td><?= esc($event['event_date']) ?></td>
            <td>
                <span class="badge bg-info">
                    <?= esc($event['registered_count'] ?? 0) ?>
                </span>
            </td>

            <td>
                <a href="/admin/events/edit/<?= $event['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="/admin/events/delete/<?= $event['id'] ?>" class="btn btn-sm btn-danger"
                onclick="return confirm('Are you sure?')">Delete</a>
            </td>
            
        </tr>
    <?php endforeach;?>
</table>
<?= view('template/footer') ?>