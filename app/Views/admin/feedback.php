<?= view('template/header') ?>

<h1 class="mb-3">Event Feedback</h1>

<?php if (empty($feedback)): ?>
    <p>No feedback submitted yet.</p>
<?php else: ?>
    <table class="table table-bordered">
        <tr class="table-dark">
            <th>Event</th>
            <th>Registrant</th>
            <th>Rating</th>
            <th>Comment</th>
            <th>Date</th>
        </tr>

        <?php foreach ($feedback as $fb): ?>
            <tr>
                <td><?= esc($fb['event_title']) ?></td>
                <td>
                    <?= esc($fb['registrant_name']) ?><br>
                    <small class="text-muted"><?= esc($fb['registrant_email']) ?></small>
                </td>
                <td>
                    <span class="badge bg-<?= $fb['rating'] >= 4 ? 'success' : ($fb['rating'] >= 3 ? 'warning' : 'danger') ?>">
                        <?= esc($fb['rating']) ?> ⭐
                    </span>
                </td>
                <td><?= esc($fb['comment'] ?? 'No comment') ?></td>
                <td><?= esc($fb['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?= view('template/footer') ?>