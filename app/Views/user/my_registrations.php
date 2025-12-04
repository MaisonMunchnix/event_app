<?= view('template/header') ?>

<h1 class="mb-3">My Registrations</h1>

<?php if (empty($regs)): ?>
    <p>No event registration found.</p>
<?php else: ?>
    <table class="table table-bordered">
        <tr class="table-dark">
            <th>Event ID</th>
            <th>Status</th>
            <th>Date Registered</th>
            <th>Attendance</th>
        </tr>

        <?php foreach ($regs as $fb): ?>
            <tr>
                <td><?= esc($fb['event_id']) ?></td>
                <td>
                    <?= esc($fb['status']) ?><br>
                </td>
                <td><?= esc(date('M d, Y ', strtotime($fb['created_at']))) ?></td>
                <td></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?= view('template/footer') ?>