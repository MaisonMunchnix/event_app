<?= view('template/header') ?>

<h1 class="mb-3">Submit Feedback for <?= esc($event['title']) ?></h1>

<?php if (isset($registration)): ?>
    <div class="alert alert-info mb-3">
        <strong>Registration Found:</strong> <?= esc($registration['name']) ?> (<?= esc($registration['email']) ?>)
    </div>
    <input type="hidden" name="registration_id" id="registration_id" value="<?= $registration['id'] ?>">
<?php endif; ?>

<form action="/feedback/store" method="post">
    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">

    <?php if (!isset($registration)): ?>
        <div class="mb-3">
            <label>Find Your Registration</label>
            <p class="text-muted small">Enter the email you used to register for this event</p>
            <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
        </div>
        <button type="submit" formaction="/feedback/find" class="btn btn-primary mb-4">Find Registration</button>
        <hr class="my-4">
    <?php else: ?>
        <input type="hidden" name="registration_id" value="<?= $registration['id'] ?>">
    <?php endif; ?>

    <?php if (isset($registration)): ?>
        <div class="mb-3">
            <label>Rating <span class="text-danger">*</span></label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="rating" id="rating1" value="1" required>
                <label class="form-check-label" for="rating1">1 - Poor</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="rating" id="rating2" value="2" required>
                <label class="form-check-label" for="rating2">2 - Fair</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="rating" id="rating3" value="3" required>
                <label class="form-check-label" for="rating3">3 - Good</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="rating" id="rating4" value="4" required>
                <label class="form-check-label" for="rating4">4 - Very Good</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="rating" id="rating5" value="5" required>
                <label class="form-check-label" for="rating5">5 - Excellent</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="comment">Comment (Optional)</label>
            <textarea name="comment" id="comment" class="form-control" rows="4" maxlength="500" placeholder="Share your thoughts about this event..."></textarea>
            <small class="text-muted">Maximum 500 characters</small>
        </div>

        <button type="submit" class="btn btn-success">Submit Feedback</button>
        <a href="/events" class="btn btn-secondary">Cancel</a>
    <?php endif; ?>
</form>

<?= view('template/footer') ?>