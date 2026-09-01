<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ClientDocument;
use App\Models\StaffDocument;
use Illuminate\Support\Str;

class AdminDocumentController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'clients');

        // Client Documents
        $clientDocuments = ClientDocument::with('client')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'client_page');

        // Staff Employee Documents (Resume, Experience, ID Proofs)
        $staffDocuments = StaffDocument::with('staff')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'staff_page');

        return view('admin.documents.index', compact('clientDocuments', 'staffDocuments', 'activeTab'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Verified,Rejected',
            'type' => 'nullable|in:client,staff'
        ]);

        if ($request->get('type') === 'staff') {
            $doc = StaffDocument::findOrFail($id);
            $doc->update(['status' => $request->status]);
            return back()->with('success', "Staff document '{$doc->document_name}' status updated to {$request->status}.");
        }

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

        return back()->with('success', 'Client document status updated successfully.');
    }

    public function download($id, Request $request)
    {
        if ($request->get('type') === 'staff') {
            $doc = StaffDocument::findOrFail($id);
            if (Storage::disk('public')->exists($doc->file_path)) {
                $extension = pathinfo($doc->file_path, PATHINFO_EXTENSION);
                $downloadName = Str::slug($doc->document_name) . '.' . $extension;
                return response()->download(storage_path('app/public/' . $doc->file_path), $downloadName);
            }
            return back()->with('error', 'Staff file not found on disk.');
        }

        $document = ClientDocument::findOrFail($id);
        
        if (Storage::disk('public')->exists($document->file_path)) {
            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
            $downloadName = Str::slug($document->document_name) . '.' . $extension;
            return response()->download(storage_path('app/public/' . $document->file_path), $downloadName);
        }
        
        return back()->with('error', 'File not found on disk.');
    }
}
