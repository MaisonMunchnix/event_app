# FeedbackModel Implementation Guide

This guide will walk you through implementing a feedback/review system for your event app. Users will be able to submit ratings and comments after registering for events.

---

## Step 1: Create Database Migration

**File to create:** `app/Database/Migrations/[timestamp]_CreateFeedbackTable.php`

Replace `[timestamp]` with current date/time format: `Y-m-d-His_` (e.g., `2025-12-02-143000_CreateFeedbackTable.php`)

**Code to paste:**

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFeedbackTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'event_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'registration_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'rating' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => 'Rating from 1 to 5'
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('event_id');
        $this->forge->addKey('registration_id');
        $this->forge->createTable('feedback');
    }

    public function down()
    {
        $this->forge->dropTable('feedback');
    }
}
```

**After creating the file:**

- Run the migration: `php spark migrate` (in your terminal/command prompt)

---

## Step 2: Create FeedbackModel

**File to create:** `app/Models/FeedbackModel.php`

**Code to paste:**

```php
<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedbackModel extends Model
{
    protected $table            = 'feedback';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['event_id', 'registration_id', 'rating', 'comment'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
```

---

## Step 3: Create FeedbackController

**File to create:** `app/Controllers/FeedbackController.php`

**Code to paste:**

```php
<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FeedbackModel;
use App\Models\EventModel;
use App\Models\RegistrationModel;

class FeedbackController extends BaseController
{
    public function create($eventId)
    {
        $eventModel = new EventModel();
        $registrationModel = new RegistrationModel();

        $event = $eventModel->find($eventId);

        if (!$event) {
            return redirect()->to('/')->with('error', 'Event not found');
        }

        // Get registration by email (you might want to use session or different method)
        // For now, we'll let them enter email to find their registration
        $data = [
            'event' => $event
        ];

        return view('feedback/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'event_id' => 'required|integer',
            'registration_id' => 'required|integer',
            'rating' => 'required|integer|greater_than[0]|less_than[6]',
            'comment' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $feedbackModel = new FeedbackModel();
        $registrationModel = new RegistrationModel();

        $eventId = $this->request->getPost('event_id');
        $registrationId = $this->request->getPost('registration_id');

        // Verify registration exists and belongs to this event
        $registration = $registrationModel
            ->where('id', $registrationId)
            ->where('event_id', $eventId)
            ->first();

        if (!$registration) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Registration not found or invalid.');
        }

        // Check if feedback already exists for this registration
        $existing = $feedbackModel
            ->where('registration_id', $registrationId)
            ->first();

        if ($existing) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'You have already submitted feedback for this event.');
        }

        $data = [
            'event_id' => $eventId,
            'registration_id' => $registrationId,
            'rating' => $this->request->getPost('rating'),
            'comment' => $this->request->getPost('comment')
        ];

        if ($feedbackModel->insert($data)) {
            return redirect()
                ->to('/events')
                ->with('success', 'Thank you for your feedback!');
        }

        return redirect()
            ->back()
            ->with('error', 'Failed to submit feedback.');
    }

    public function findByEmail()
    {
        $email = $this->request->getPost('email');
        $eventId = $this->request->getPost('event_id');

        if (empty($email) || empty($eventId)) {
            return redirect()
                ->back()
                ->with('error', 'Email and event are required.');
        }

        $registrationModel = new RegistrationModel();
        $registration = $registrationModel
            ->where('email', $email)
            ->where('event_id', $eventId)
            ->first();

        if (!$registration) {
            return redirect()
                ->back()
                ->with('error', 'No registration found with this email for this event.');
        }

        $feedbackModel = new FeedbackModel();
        $existingFeedback = $feedbackModel
            ->where('registration_id', $registration['id'])
            ->first();

        if ($existingFeedback) {
            return redirect()
                ->back()
                ->with('error', 'You have already submitted feedback for this event.');
        }

        $eventModel = new EventModel();
        $event = $eventModel->find($eventId);

        $data = [
            'event' => $event,
            'registration' => $registration
        ];

        return view('feedback/create', $data);
    }
}
```

---

## Step 4: Create Feedback Views

### 4a. Create Feedback Form View

**File to create:** `app/Views/feedback/create.php`

**Code to paste:**

```php
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
```

---

## Step 5: Add Routes

**File to edit:** `app/Config/Routes.php`

**Find the existing routes section and add these lines:**

```php
// Add these routes for feedback functionality
$routes->get('/feedback/create/(:num)', 'FeedbackController::create/$1');
$routes->post('/feedback/find', 'FeedbackController::findByEmail');
$routes->post('/feedback/store', 'FeedbackController::store');
```

**Note:** Add these routes in an appropriate location (usually after your event routes).

---

## Step 6: Add "Leave Feedback" Link to Event List

**File to edit:** `app/Views/events/list.php`

**Find the Register button section and add a feedback link:**

```php
<?php foreach($events as $event):?>

<div class="card mb-3">
    <div class="card-body p-4">
        <h5 class="card-title"><?= esc($event['title']) ?></h5>
        <p class="card-text"><?= esc($event['description']) ?></p>
        <p class="card-text">
            <strong>Venue:</strong> <?= esc($event['venue']) ?> |
            <strong>Date:</strong> <?= esc($event['event_date']) ?> |
            <strong>Registered:
                <span class="badge bg-success"><?= esc($event['registered_count']) ?></span>
            </strong>
        </p>
        <a href="/events/register/<?= $event['id'] ?>" class="btn btn-primary">Register</a>
        <a href="/feedback/create/<?= $event['id'] ?>" class="btn btn-outline-secondary">Leave Feedback</a>
    </div>
</div>

<?php endforeach;?>
```

---

## Step 7: (Optional) Show Average Rating on Event List

**File to edit:** `app/Controllers/EventController.php`

**In the `index()` method, add feedback count/rating calculation:**

```php
public function index()
{
    $model = new EventModel();
    $registration = new RegistrationModel();
    $feedbackModel = new FeedbackModel(); // Add this line

    $events = $model->orderBy('event_date', 'ASC')->findAll();

    foreach ($events as $key => $event) {
        $events[$key]['registered_count'] = $registration
            ->where('event_id', $event['id'])
            ->countAllResults();

        // Add average rating calculation
        $feedbacks = $feedbackModel->where('event_id', $event['id'])->findAll();
        if (!empty($feedbacks)) {
            $totalRating = array_sum(array_column($feedbacks, 'rating'));
            $events[$key]['average_rating'] = round($totalRating / count($feedbacks), 1);
            $events[$key]['feedback_count'] = count($feedbacks);
        } else {
            $events[$key]['average_rating'] = 0;
            $events[$key]['feedback_count'] = 0;
        }
    }
    return view('events/list', ['events' => $events]);
}
```

**Then update the view to show ratings:**

**File to edit:** `app/Views/events/list.php`

**Add rating display in the event card:**

```php
<p class="card-text">
    <strong>Venue:</strong> <?= esc($event['venue']) ?> |
    <strong>Date:</strong> <?= esc($event['event_date']) ?> |
    <strong>Registered:
        <span class="badge bg-success"><?= esc($event['registered_count']) ?></span>
    </strong>
    <?php if (isset($event['average_rating']) && $event['average_rating'] > 0): ?>
        | <strong>Rating:</strong>
        <span class="badge bg-warning text-dark">
            <?= esc($event['average_rating']) ?> ⭐ (<?= esc($event['feedback_count']) ?> reviews)
        </span>
    <?php endif; ?>
</p>
```

---

## Step 8: (Optional) Admin View - See All Feedback

**File to edit:** `app/Controllers/AdminController.php`

**Add a new method to view feedback:**

```php
public function feedback()
{
    $feedbackModel = new FeedbackModel();
    $eventModel = new EventModel();
    $registrationModel = new RegistrationModel();

    $allFeedback = $feedbackModel->orderBy('created_at', 'DESC')->findAll();

    // Attach event and registration details
    foreach ($allFeedback as $key => $feedback) {
        $event = $eventModel->find($feedback['event_id']);
        $registration = $registrationModel->find($feedback['registration_id']);

        $allFeedback[$key]['event_title'] = $event['title'] ?? 'N/A';
        $allFeedback[$key]['registrant_name'] = $registration['name'] ?? 'N/A';
        $allFeedback[$key]['registrant_email'] = $registration['email'] ?? 'N/A';
    }

    $data = [
        'feedback' => $allFeedback
    ];

    return view('admin/feedback', $data);
}
```

**File to create:** `app/Views/admin/feedback.php`

**Code to paste:**

```php
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
```

**Add route for admin feedback:**

**File to edit:** `app/Config/Routes.php`

```php
$routes->get('/admin/feedback', 'AdminController::feedback');
```

**Add link in admin dashboard (optional):**

**File to edit:** `app/Views/admin/dashboard.php`

Add a new card or link to view feedback in the dashboard.

---

## Testing Checklist

- [ ] Run migration: `php spark migrate`
- [ ] Register for an event
- [ ] Go to event list and click "Leave Feedback"
- [ ] Enter your registration email
- [ ] Submit feedback with rating and comment
- [ ] Verify feedback appears in admin panel (if you added that feature)
- [ ] Try submitting feedback twice (should show error)
- [ ] Check that average rating shows on event list (if you added that feature)

---

## Troubleshooting

**Migration fails:**

- Check database connection in `app/Config/Database.php`
- Make sure table name doesn't already exist

**Can't find registration:**

- Verify the email matches exactly (case-sensitive in some databases)
- Check that registration exists in the database

**Routes not working:**

- Clear CodeIgniter cache: Delete files in `writable/cache/`
- Check `app/Config/Routes.php` syntax

---

## Next Steps (Optional Enhancements)

1. Add star rating visual component (JavaScript)
2. Add feedback moderation (approve/reject)
3. Add email notification when feedback is submitted
4. Add pagination for feedback list
5. Add filtering by rating (1-5 stars) in admin
6. Add feedback summary statistics in admin dashboard

---

**Good luck with your implementation! 🚀**
