<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\StaffDocument;
use Illuminate\Support\Str;

class StaffDocumentController extends Controller
{
    public function index()
    {
        $staff = Auth::guard('staff')->user();

        // All documents uploaded by this staff member (resume, experience, ID proofs, etc.)
        $myDocuments = StaffDocument::where('staff_id', $staff->id)
            ->latest()
            ->paginate(10);

        $documentTypes = [
            'Resume / Curriculum Vitae (CV)',
            'Experience Certificate',
            'Relieving Letter from Previous Employer',
            'Educational Degree & Marksheets',
            'Government ID Proof (Aadhaar / PAN / Passport)',
            'Offer Letter & Signed NDA Agreement',
            'Technical Certifications & Training',
            'Salary Slips / Bank Statement',
            'Other Document'
        ];

        return view('staff.documents.index', compact('staff', 'myDocuments', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_name' => 'required|string|max:255',
            'document_type' => 'required|string|max:100',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240', // 10MB max
        ]);

        $staff = Auth::guard('staff')->user();
        $file = $request->file('file');
        
        $originalExt = $file->getClientOriginalExtension();
        $cleanName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $fileName = time() . '_' . $cleanName . '.' . $originalExt;
        
        $filePath = $file->storeAs('documents/staff/' . $staff->id, $fileName, 'public');

        StaffDocument::create([
            'staff_id' => $staff->id,
            'document_name' => $request->document_name,
            'document_type' => $request->document_type,
            'file_path' => $filePath,
            'file_type' => $originalExt,
            'status' => 'Pending',
        ]);

        return redirect()->route('staff.documents.index')->with('success', 'Document uploaded successfully! It is now securely submitted for Admin HR review.');
    }

    public function download($id)
    {
        $staff = Auth::guard('staff')->user();
        $document = StaffDocument::where('staff_id', $staff->id)->where('id', $id)->firstOrFail();

        if (Storage::disk('public')->exists($document->file_path)) {
            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
            $downloadName = Str::slug($document->document_name) . '.' . $extension;
            return response()->download(storage_path('app/public/' . $document->file_path), $downloadName);
        }

        return back()->with('error', 'File not found on storage.');
    }

    public function destroy($id)
    {
        $staff = Auth::guard('staff')->user();
        $document = StaffDocument::where('staff_id', $staff->id)->where('id', $id)->firstOrFail();

        // Only allow deleting if still pending
        if ($document->status === 'Verified') {
            return back()->with('error', 'Verified documents cannot be deleted. Please contact your administrator.');
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('staff.documents.index')->with('success', 'Document removed successfully.');
    }
}
