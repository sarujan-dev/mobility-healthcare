<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(15);

        // Mark all as read when admin views the list
        ContactMessage::where('is_read', false)->update(['is_read' => true]);

        return view('admin.contact-messages.index', compact('messages'));
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return back()->with('success', 'Message deleted.');
    }
}
