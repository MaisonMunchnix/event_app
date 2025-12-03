<?= view('template/header') ?>

<h1 class="mb-3"><?= esc($event['title']) ?></h1>
<p class="mb-1 fw-meduim"><strong>Date: </strong><?= esc(date('M d, Y', strtotime($event['event_date']))) ?></p>
<p class="mb-4 fw-meduim"><strong>Venue: </strong><?= esc($event['venue']) ?></p>


<h3>Participants</h3>
<table class="table table-bordered">
<tr class="table-dark">
    <th>Name</th>
    <th>Email</th>
    <th>Event Title</th>
    <th>Status</th>
    <th class="text-center">Action</th>
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
            <a href="/admin/approve/<?= $r['id'] ?>" class="btn btn-sm btn-primary">Approve</a>
            <?php else: ?>
                <button class="btn btn-sm btn-secondary" disabled>Approved</button>
            <?php endif; ?>

        </td>

        <td class="text-center">
            <?php if($r['status'] === 'Approved'): ?>
                <button class="btn btn-sm btn-warning">Confirm Attendance</button>
            <?php endif;?>
        </td>
    </tr>
<?php endforeach; ?>
</table>

<?= view('template/footer') ?>