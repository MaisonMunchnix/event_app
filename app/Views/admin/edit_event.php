<?= view('template/header') ?>

<h1 class="mb-3">Edit event</h1>

<form action="/admin/events/update/<?= $event['id'] ?>" method="post">
    <div class="mb-3">
        <label for="">Title:</label>
        <input type="text" name="title" class="form-control" value="<?= esc($event['title']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="">Description:</label>
        <input type="text" name="description" class="form-control" value="<?= esc($event['description']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="">Venue:</label>
        <input type="text" name="venue" class="form-control" value="<?= esc($event['venue']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="">Date:</label>
        <input type="date" name="event_date" class="form-control" value="<?= esc($event['event_date']) ?>" required>
    </div>

    <button tyle="submit" class="btn btn-success">Update Event</button>

</form>
<?= view('template/footer') ?>