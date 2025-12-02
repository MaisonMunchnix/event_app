<?= view('template/header') ?>

<h1 class="mb-3">Add new event</h1>

<form action="/admin/events/store" method="post">
    <div class="mb-3">
        <label for="">Title:</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="">Description:</label>
        <input type="text" name="description" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="">Venue:</label>
        <input type="text" name="venue" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="">Date:</label>
        <input type="date" name="event_date" class="form-control" required>
    </div>

    <button tyle="submit" class="btn btn-success">Create Event</button>

</form>
<?= view('template/footer') ?>