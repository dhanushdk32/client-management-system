<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ClientDocument;
use Illuminate\Support\Str;

class StaffDocumentController extends Controller
{
    public function index(Request $request)
    {
        $staff = Auth::guard('staff')->user();
        $assignedClientIds = $staff->assignedClients()->pluck('client_tbl.client_id')->toArray();

        $query = ClientDocument::with('client')
            ->whereIn('client_id', $assignedClientIds)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('document_name', 'like', "%{$search}%")
                  ->orWhere('document_type', 'like', "%{$search}%")
                  ->orWhereHas('client', function($cq) use ($search) {
                      $cq->where('client_name', 'like', "%{$search}%")
                        ->orWhere('client_company', 'like', "%{$search}%");
                  });
            });
        }

        $documents = $query->paginate(10);

        return view('staff.documents.index', compact('documents'));
    }

    public function update(Request $request, $id)
    {
        $staff = Auth::guard('staff')->user();
        $assignedClientIds = $staff->assignedClients()->pluck('client_tbl.client_id')->toArray();

        $request->validate([
            'status' => 'required|in:Pending,Verified,Rejected',
        ]);

        $document = ClientDocument::findOrFail($id);

        if (!in_array($document->client_id, $assignedClientIds)) {
            return back()->with('error', 'Unauthorized access to this document.');
        }

        $document->update(['status' => $request->status]);

        if ($request->status !== 'Pending') {
            \App\Models\Notification::create([
                'client_id' => $document->client_id,
                'title' => 'Document ' . $request->status,
                'message' => 'Your document "' . $document->document_name . '" has been reviewed and marked as ' . strtolower($request->status) . '.',
                'is_read' => 0
            ]);
        }

        return back()->with('success', 'Client document status updated successfully.');
    }

    public function download($id)
    {
        $staff = Auth::guard('staff')->user();
        $assignedClientIds = $staff->assignedClients()->pluck('client_tbl.client_id')->toArray();

        $document = ClientDocument::findOrFail($id);

        if (!in_array($document->client_id, $assignedClientIds)) {
            return back()->with('error', 'Unauthorized access to this document.');
        }
        
        if (Storage::disk('public')->exists($document->file_path)) {
            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
            $downloadName = Str::slug($document->document_name) . '.' . $extension;
            return response()->download(storage_path('app/public/' . $document->file_path), $downloadName);
        }
        
        return back()->with('error', 'File not found on disk.');
    }
}
