<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ClientDocument;

class AdminDocumentController extends Controller
{
    public function index(Request $request)
    {
        $documents = ClientDocument::with('client')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.documents.index', compact('documents'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Verified,Rejected'
        ]);

        $document = ClientDocument::findOrFail($id);
        $document->update(['status' => $request->status]);

        if ($request->status !== 'Pending') {
            \App\Models\Notification::create([
                'client_id' => $document->client_id,
                'title' => 'Document ' . $request->status,
                'message' => 'Your document "' . $document->document_name . '" has been ' . strtolower($request->status) . '.',
                'is_read' => 0
            ]);
        }

        return back()->with('success', 'Document status updated successfully.');
    }

    public function download($id)
    {
        $document = ClientDocument::findOrFail($id);
        
        if (Storage::disk('public')->exists($document->file_path)) {
            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
            $downloadName = \Illuminate\Support\Str::slug($document->document_name) . '.' . $extension;
            return response()->download(storage_path('app/public/' . $document->file_path), $downloadName);
        }
        
        return back()->with('error', 'File not found.');
    }
}
