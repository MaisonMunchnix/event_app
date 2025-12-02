<?= view('template/header') ?>

<h3>All Registrations</h3>

<form method="get" class="mb-3">
    <div class="row">
        <div class="col-md-3">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Status</option>
                <option value="Pending" <?= ($statusFilter === 'Pending') ? 'selected' : '' ?>>Pending</option>
                <option value="Approved" <?= ($statusFilter === 'Approved') ? 'selected' : '' ?>>Approved</option>
            </select>
        </div>
    </div>
</form>


<table class="table table-bordered">
<tr class="table-dark">
    <th>Name</th>
    <th>Email</th>
    <th>Event Title</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php foreach($filteredRegistrations as $r): ?>
<?php 
    $rowClass = $r['status'] === 'Pending' ? 'table-warning' : 'table-success';
?>
<tr class="<?= $rowClass ?>">
    <td><?= esc($r['name']) ?></td>
    <td><?= esc($r['email']) ?></td>
    <td><?= esc($r['event_title'] ?? 'Unknown Event') ?></td>
    <td>
        <?php if($r['status'] === 'Pending'): ?>
            <span class="badge bg-warning text-dark">Pending</span>
        <?php else: ?>
            <span class="badge bg-success">Approved</span>
        <?php endif; ?>
    </td>
    <td>
        <?php if($r['status'] === 'Pending'): ?>
        <a href="/admin/approve/<?= $r['id'] ?>" class="btn btn-sm btn-primary">Approve</a>
        <?php else: ?>
        <button class="btn btn-sm btn-secondary" disabled>Approved</button>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
<?= view('template/footer') ?>