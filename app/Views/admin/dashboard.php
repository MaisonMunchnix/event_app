<?= view('template/header') ?>
<h1 class="mb-4">Admin Dashboard</h1>
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Total Events</div>
            <div class="card-body">
                <h5 class="card-title"><?= count($events ?? []) ?></h5>
                <a href="/admin/events" class="btn btn-light btn-sm">Manage Events</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Total Registrations</div>
            <div class="card-body">
                <h5 class="card-title"><?= count($registrations ?? []) ?></h5>
                <a href="/admin/registrations" class="btn btn-light btn-sm">View Registrations</a>
            </div>
        </div>
    </div>
</div>

<div  class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">Pending Approvals</div>
            <div class="card-body">
                <h5 class="card-title">
                    <?= count(array_filter($registrations ?? [], fn($r) => $r['status'] === 'Pending')) ?>
                </h5>
                <a href="/admin/registrations" class="btn btn-light btn-sm">Approve Now</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3">
            <div class="card-header">Events Feedback</div>
            <div class="card-body">
                <h5 class="card-title">
                    <?= count(array_filter($registrations ?? [], fn($r) => $r['status'] === 'Pending')) ?>
                </h5>
                <a href="/admin/feedback" class="btn btn-light btn-sm">View Feedback</a>
            </div>
        </div>
    </div>
</div>

<h3>Recent Registrations</h3>

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