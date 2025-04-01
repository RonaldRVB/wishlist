<?php

namespace App\Http\Controllers;

use App\Models\LegalDocument;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LegalDocumentController extends Controller
{
    public function index()
    {
        $documents = LegalDocument::orderByDesc('created_at')->get();

        return Inertia::render('Documents/Index', [
            'documents' => $documents,
        ]);
    }

    public function create()
    {
        return Inertia::render('Documents/Create');
    }

    public function edit(LegalDocument $document)
    {
        return Inertia::render('Documents/Edit', [
            'document' => $document,
        ]);
    }

    public function show(LegalDocument $document)
    {
        return Inertia::render('Documents/Show', [
            'document' => $document,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'is_active' => ['boolean'],
        ]);

        // Désactiver les autres documents si celui-ci est actif
        if (!empty($validated['is_active'])) {
            LegalDocument::where('is_active', true)->update(['is_active' => false]);
        }

        LegalDocument::create($validated);

        return redirect()->route('documents.index')->with('success', 'Document créé avec succès.');
    }

    public function update(Request $request, LegalDocument $document)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'is_active' => ['boolean'],
        ]);

        if (!empty($validated['is_active'])) {
            LegalDocument::where('is_active', true)->where('id', '!=', $document->id)->update(['is_active' => false]);
        }

        $document->update($validated);

        return redirect()->route('documents.index')->with('success', 'Document mis à jour avec succès.');
    }

    public function destroy(LegalDocument $document)
    {
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document supprimé.');
    }
}
