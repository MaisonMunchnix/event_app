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
}
