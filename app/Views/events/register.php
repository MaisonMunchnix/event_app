<?= view('template/header') ?>
<h1 class="mb-3">Register for <?= esc($event['title']) ?></h1>
<form action="/events/store" method="post">
    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
    <div class="mb-3">
        <label>Name:</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Submit Registration</button>
</form>
<?= view('template/footer') ?>
