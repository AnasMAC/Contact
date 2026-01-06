<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 1. Fetch all contacts
        $contacts = Contact::all();

        // 2. Return the view with the data
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate inputs
        $request->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email' // Added 'email' validation rule for safety
        ]);

        // 2. Create and Save (Simpler syntax)
        $contact = new Contact([
            'first_name' => $request->get('first_name'),
            'last_name'  => $request->get('last_name'),
            'email'      => $request->get('email'),
            'job_title'  => $request->get('job_title'),
            'city'       => $request->get('city'),
            'country'    => $request->get('country')
        ]);

        $contact->save();

        return redirect('/contacts')->with('success', 'Contact saved!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // 1. Find the contact
        $contact = Contact::find($id);

        if (!$contact) {
            return redirect('/contacts')->with('error', 'Contact not found');
        }

        // 2. Return a view showing just this contact
        // You usually create a 'contacts.show' view for this
        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // 1. Find the contact so we can fill the form
        $contact = Contact::find($id);

        if (!$contact) {
            return redirect('/contacts')->with('error', 'Contact not found');
        }

        // 2. Return the edit view
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Validate inputs again
        $request->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email'
        ]);

        // 2. Find the contact
        $contact = Contact::find($id);

        // 3. Update the fields
        $contact->first_name = $request->get('first_name');
        $contact->last_name  = $request->get('last_name');
        $contact->email      = $request->get('email');
        $contact->job_title  = $request->get('job_title');
        $contact->city       = $request->get('city');
        $contact->country    = $request->get('country');

        $contact->save();

        return redirect('/contacts')->with('success', 'Contact updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // 1. Find the contact
        $contact = Contact::find($id);

        // 2. Delete it
        $contact->delete();

        // 3. Redirect back to list
        return redirect('/contacts')->with('success', 'Contact deleted!');
    }
}































