<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ClientDocument;
use Carbon\Carbon;

class ClientDocumentController extends Controller
{
    public function index()
    {
        $client_id = Auth::guard('client')->user()->client_id;
        $documents = ClientDocument::where('client_id', $client_id)->orderBy('created_at', 'desc')->get();
        return view('client.documents.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|max:100',
            'document_name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240', // 10MB max
        ]);

        $client_id = Auth::guard('client')->user()->client_id;
        
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/clients/' . $client_id, $fileName, 'public');

        ClientDocument::create([
            'client_id' => $client_id,
            'uploaded_by' => Auth::guard('client')->user()->id,
            'document_type' => $request->document_type,
            'document_name' => $request->document_name,
            'file_path' => $filePath,
            'status' => 'Pending',
            'approval_status' => 'Pending Approval',
        ]);

        \App\Models\Notification::create([
            'client_id' => $client_id,
            'title' => 'Document Uploaded',
            'message' => 'Your document "' . $request->document_name . '" has been successfully uploaded and is pending verification.',
            'is_read' => 0
        ]);

        return redirect()->route('client.documents.index')->with('success', 'Document uploaded successfully. Awaiting verification.');
    }

    public function approveDeliverable(Request $request, $id)
    {
        $client_id = Auth::guard('client')->user()->client_id;
        $document = ClientDocument::where('client_id', $client_id)->where('id', $id)->firstOrFail();

        $document->update([
            'approval_status' => 'Approved',
            'client_feedback' => $request->input('client_feedback', 'Approved by Client.'),
        ]);

        \App\Models\Notification::create([
            'client_id' => $client_id,
            'title' => 'Deliverable Approved',
            'message' => 'You have formally approved "' . $document->document_name . '". Your project team leader has been notified.',
            'is_read' => 0
        ]);

        return back()->with('success', 'Deliverable successfully approved and sign-off recorded!');
    }

    public function requestRevision(Request $request, $id)
    {
        $request->validate([
            'client_feedback' => 'required|string|max:1000'
        ]);

        $client_id = Auth::guard('client')->user()->client_id;
        $document = ClientDocument::where('client_id', $client_id)->where('id', $id)->firstOrFail();

        $document->update([
            'approval_status' => 'Revision Requested',
            'client_feedback' => $request->client_feedback,
        ]);

        \App\Models\Notification::create([
            'client_id' => $client_id,
            'title' => 'Revision Requested',
            'message' => 'Revision requested for "' . $document->document_name . '". Feedback forwarded to the engineering lead.',
            'is_read' => 0
        ]);

        return back()->with('success', 'Revision request and feedback submitted to your technical lead.');
    }

    public function download($id)
    {
        $client_id = Auth::guard('client')->user()->client_id;
        $document = ClientDocument::where('client_id', $client_id)->where('id', $id)->firstOrFail();
        
        if (Storage::disk('public')->exists($document->file_path)) {
            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
            $downloadName = \Illuminate\Support\Str::slug($document->document_name) . '.' . $extension;
            return response()->download(storage_path('app/public/' . $document->file_path), $downloadName);
        }
        
        return back()->with('error', 'File not found on server.');
    }
}
