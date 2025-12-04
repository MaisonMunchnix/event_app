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

        //verify registration exists and belongs to this event
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

        //check if feedback already exists for this registration
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
                ->to('/')
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
