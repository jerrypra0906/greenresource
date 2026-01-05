<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inquiries = Inquiry::orderBy('submitted_at', 'desc')->paginate(20);
        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Inquiry $inquiry)
    {
        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'status' => 'required|in:new,handled,archived',
        ]);

        $inquiry->update(['status' => $request->status]);

        return redirect()->route('admin.inquiries.show', $inquiry)
            ->with('success', 'Inquiry status updated successfully.');
    }
}
