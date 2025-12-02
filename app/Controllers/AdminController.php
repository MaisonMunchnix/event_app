<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\RegistrationModel;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $eventModel = new EventModel();
        $registrationModel = new RegistrationModel();

        $allRegistrations = $registrationModel->findAll();

        $statusFilter = $this->request->getGet('status');

        if (!empty($statusFilter)) {
            $filterRegistrations = $registrationModel->where('status', $statusFilter)->findAll();
        } else {
            $filterRegistrations = $allRegistrations;
        }

        $events = $eventModel->findAll();
        $eventLookup = [];
        foreach ($events as $event) {
            $eventLookup[$event['id']] = $event['title'];
        }

        foreach ($filterRegistrations as $key => $registration) {
            $filterRegistrations[$key]['event_title'] = $eventLookup[$registration['event_id']] ?? 'Unknown Event';
        }

        $data = [
            'events' => $events,
            'registrations' => $allRegistrations,
            'filteredRegistrations' => $filterRegistrations,
            'statusFilter' => $statusFilter,
        ];
        return view('admin/dashboard', $data);
    }

    public function events()
    {

        $eventModel = new EventModel();
        $registrationModel = new RegistrationModel();

        $events = $eventModel->orderBy('event_date', 'ASC')->findAll();

        //count all
        foreach ($events as $key => $event) {
            $events[$key]['registered_count'] = $registrationModel
                ->where('event_id', $event['id'])
                ->countAllResults();
        }

        $data = [
            'events' => $events
        ];

        return view('admin/events', $data);
    }

    public function createEvent()
    {
        return view('admin/create_event');
    }

    public function storeEvent()
    {
        $eventModel = new EventModel();
        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'venue' => $this->request->getPost('venue'),
            'event_date' => $this->request->getPost('event_date')
        ];

        $eventModel->insert($data);

        return redirect()
            ->to('/admin/events')
            ->with('success', "Event created successfuly.");
    }

    public function editEvent($id)
    {
        $eventModel = new EventModel();
        $event = $eventModel->find($id);

        if (!$event) {
            return redirect()
                ->to('/admin/events')
                ->with('error', 'Event not found.');
        }

        return view('admin/edit_event', ['event' => $event]);
    }

    public function updateEvent($id)
    {
        $eventModel = new EventModel();

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'venue' => $this->request->getPost('venue'),
            'event_date' => $this->request->getPost('event_date'),
        ];

        $eventModel->update($id, $data);

        return redirect()
            ->to('admin/events')
            ->with('success', 'Event updated successfully.');
    }

    public function deleteEvent($id)
    {
        $eventModel = new EventModel();

        $event = $eventModel->find($id);

        if (!$event) {
            return redirect()
                ->to('/admin/events')
                ->with('error', 'Event not found.');
        }

        $eventModel->delete($id);
        return redirect()
            ->to('admin/events')
            ->with('error', 'Event deleted successfully.');
    }

    public function registrations()
    {
        $eventModel = new EventModel();
        $registrationModel = new RegistrationModel();

        $allRegistrations = $registrationModel->findAll();

        $statusFilter = $this->request->getGet('status');

        if (!empty($statusFilter)) {
            $filterRegistrations = $registrationModel->where('status', $statusFilter)->findAll();
        } else {
            $filterRegistrations = $allRegistrations;
        }

        $events = $eventModel->findAll();
        $eventLookup = [];
        foreach ($events as $event) {
            $eventLookup[$event['id']] = $event['title'];
        }

        foreach ($filterRegistrations as $key => $registration) {
            $filterRegistrations[$key]['event_title'] = $eventLookup[$registration['event_id']] ?? 'Unknown Event';
        }

        $data = [
            'registrations' => $allRegistrations,
            'filteredRegistrations' => $filterRegistrations,
            'statusFilter' => $statusFilter,
        ];
        return view('admin/registrations', $data);
    }

    public function approve($id)
    {
        $registrationModel = new RegistrationModel();
        $registration = $registrationModel->find($id);

        if (!$registration) {
            return redirect()
                ->back()
                ->with('error', 'Registration not found');
        }
        $registrationModel->update($id, ['status' => 'Approved']);

        return redirect()
            ->back()
            ->with('success', 'Registration successfully approved.');
    }
}
