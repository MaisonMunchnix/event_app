<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RegistrationModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\EventModel;

class EventController extends BaseController
{
    public function index()
    {
        $model = new EventModel();
        $registration = new RegistrationModel();
        $events = $model->orderBy('event_date', 'ASC')->findAll();
        // $data['events'] = $model->orderBy('event_date', 'ASC')->findAll();

        foreach ($events as $key => $event) {
            $events[$key]['registered_count'] = $registration
                ->where('event_id', $event['id'])
                ->countAllResults();
        }
        return view('events/list', ['events' => $events]);
    }

    public function register($id)
    {
        $model = new EventModel();
        $data['event'] = $model->find($id);
        if (!$data['event']) {
            return redirect()->to('/')->with('error', 'Event not found');
        }
        return view('events/register', $data);
    }

    public function store()
    {

        $validation = \Config\Services::validation();

        //define rules
        $rules = [
            'event_id' => 'required|integer',
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            //validation failed
            return redirect()
                ->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $registrationModel = new RegistrationModel();

        $event_id = $this->request->getPost('event_id');
        $email = $this->request->getPost('email');
        $existing = $registrationModel
            ->where('event_id', $event_id)
            ->where('email', $email)
            ->first();


        if ($existing) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'You have already registered for this event with this email.');
        }

        $data = [
            'event_id' => $event_id,
            'name' => $this->request->getPost('name'),
            'email' => $email,
            'status' => 'Pending'
        ];

        $insertId = $registrationModel->insert($data);

        if ($insertId) {
            $eventModel = new EventModel();
            $event = $eventModel->find($event_id);
            $registration = $registrationModel->find($insertId);

            return view('events/success', [
                'event' => $event,
                'registration' => $registration,
            ]);
        }

        return redirect()
            ->back()
            ->with('error', 'Failed to submit registration.');

        // $registrationModel = new RegistrationModel();
        // $data = [
        //     'event_id' => $this->request->getPost('event_id'),
        //     'name'=> $this->request->getPost('name'),
        //     'email' => $this->request->getPost('email'),
        // ];

        // if($registrationModel->insert($data)){
        //     return redirect()->to('/')->with('success', 'Registration submitted succesfully!');
        // }else{
        //     return redirect()->back()->with('error', 'Registration failed.');
        // }
    }
}
