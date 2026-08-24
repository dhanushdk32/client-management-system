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
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB max
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
            'status' => 'Pending'
        ]);

        \App\Models\Notification::create([
            'client_id' => $client_id,
            'title' => 'Document Uploaded',
            'message' => 'Your document "' . $request->document_name . '" has been successfully uploaded and is pending verification.',
            'is_read' => 0
        ]);

        return redirect()->route('client.documents.index')->with('success', 'Document uploaded successfully. Awaiting verification.');
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
        
        return back()->with('error', 'File not found.');
    }
}
