<?= view('template/header') ?>

<h1 class="mb-3"><?= esc($event['title']) ?></h1>

<p class="mb-1 fw-medium">
    <strong>Date:</strong> <?= esc(date('M d, Y', strtotime($event['event_date']))) ?>
</p>

<p class="mb-4 fw-medium">
    <strong>Venue:</strong> <?= esc($event['venue']) ?>
</p>

<h3>Participants</h3>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Event</th>
            <th>Status</th>
            <th class="text-center">Attendance</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($filteredRegistrations as $r): ?>
            <?php
            $rowClass = $r['status'] === 'Pending'
                ? 'table-warning'
                : 'table-success';

            $attendance = $r['attendance'] ?? null; // present / absent / null
            ?>
            <tr class="<?= $rowClass ?>">
                <td><?= esc($r['name']) ?></td>
                <td><?= esc($r['email']) ?></td>
                <td><?= esc($event['title']) ?></td>

                <td>
                    <?php if ($r['status'] === 'Pending'): ?>
                        <a href="/admin/approve/<?= $r['id'] ?>"
                            class="btn btn-sm btn-primary">
                            Approve
                        </a>
                    <?php else: ?>
                        <button class="btn btn-sm btn-secondary" disabled>
                            Approved
                        </button>
                    <?php endif; ?>
                </td>

                <td class="text-center">
                    <?php if ($r['status'] === 'Approved'): ?>
                        <a href="/admin/attendance/present/<?= $r['id'] ?>"
                            class="btn btn-sm btn-success <?= $attendance === 'present' ? 'disabled opacity-50' : '' ?>">
                            ✓
                        </a>
                        <a href="/admin/attendance/absent/<?= $r['id'] ?>"
                            class="btn btn-sm btn-danger <?= $attendance === 'absent' ? 'disabled opacity-50' : '' ?>">
                            ✗
                        </a>

                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= view('template/footer') ?>