<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{
    // Show a list of all education resources for the logged-in blood bank admin
    public function index()
    {
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        // Fetch only the educations created by the logged-in blood bank admin
        $educations = Education::where('created_by', $bloodBankAdminId)->get();
        return view('blood_bank_admin.educations.index', compact('educations'));
    }

    // Show the form to create a new education resource
    public function create()
    {
        return view('blood_bank_admin.educations.create');
    }

    // Store a new education resource in the database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Handle thumbnail upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // Get the logged-in blood bank admin's ID
        $bloodBankAdminId = auth()->guard('blood_bank_admin')->id();

        // Create a new education entry associated with the logged-in blood bank admin
        Education::create([
            'title' => $request->title,
            'content' => $request->content,
            'thumbnail' => $thumbnailPath,
            'created_by' => $bloodBankAdminId,  // Add the ID of the logged-in blood bank admin
        ]);

        return redirect()->route('blood_bank_admin.educations.index')->with('success', 'Education resource created successfully.');
    }

    // Show a list of education resources for the public (no authentication required)
    public function indexForUsers()
    {
        // Fetch all education resources (could be filtered if necessary)
        $educations = Education::all();
        return view('educations.index', compact('educations'));
    }

    // Show a specific education resource for the public
    public function showForUsers(Education $education)
    {
        return view('educations.show', compact('education'));
    }

    // Show a specific education resource for the blood bank admin
    public function show(Education $education)
    {
        // Ensure the logged-in blood bank admin has access to this education resource
        if ($education->created_by !== auth()->guard('blood_bank_admin')->id()) {
            return redirect()->route('blood_bank_admin.educations.index')->with('error', 'You do not have permission to view this resource.');
        }

        return view('blood_bank_admin.educations.show', compact('education'));
    }

    // Show the form to edit an existing education resource
    public function edit(Education $education)
    {
        // Ensure the logged-in blood bank admin has access to this education resource
        if ($education->created_by !== auth()->guard('blood_bank_admin')->id()) {
            return redirect()->route('blood_bank_admin.educations.index')->with('error', 'You do not have permission to edit this resource.');
        }

        return view('blood_bank_admin.educations.edit', compact('education'));
    }

    // Update an education resource in the database
    public function update(Request $request, Education $education)
{
    // Ensure the logged-in blood bank admin has access to this education resource
    if ($education->created_by !== auth()->guard('blood_bank_admin')->id()) {
        return redirect()->route('blood_bank_admin.educations.index')->with('error', 'You do not have permission to update this resource.');
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required',
        'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    // Handle thumbnail upload
    if ($request->hasFile('thumbnail')) {
        // Delete the old thumbnail if it exists
        if ($education->thumbnail) {
            Storage::disk('public')->delete($education->thumbnail);
        }

        // Store the new thumbnail
        $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        $education->thumbnail = $thumbnailPath;
    }

    // Update the education resource
    $education->update([
        'title' => $request->title,
        'content' => $request->content,
        'thumbnail' => $education->thumbnail,
    ]);

    return redirect()->route('blood_bank_admin.educations.index')->with('success', 'Education resource updated successfully.');
}
    // Delete an education resource
    public function destroy(Education $education)
    {
        // Ensure the logged-in blood bank admin has access to this education resource
        if ($education->created_by !== auth()->guard('blood_bank_admin')->id()) {
            return redirect()->route('blood_bank_admin.educations.index')->with('error', 'You do not have permission to delete this resource.');
        }

        $education->delete();
        return redirect()->route('blood_bank_admin.educations.index')->with('success', 'Education resource deleted successfully.');
    }
}
