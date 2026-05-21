<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use Illuminate\Http\Request;

class ProspectController extends Controller
{
    /**
     * LIST PROSPECT
     */
   public function index()
{
    $prospects = Prospect::with('user')
        ->where('user_id', auth()->id())
        ->latest()
        ->paginate(10);

    // 🔥 MY PROSPECT (ALL TIME)
    $myProspectsAllTime = Prospect::where('user_id', auth()->id())->count();

    // 🔥 MY PROSPECT (THIS MONTH)
    $myProspectsThisMonth = Prospect::where('user_id', auth()->id())
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

    return view('pages.prospects.index', compact(
        'prospects',
        'myProspectsAllTime',
        'myProspectsThisMonth'
    ));
}

    /**
     * FORM CREATE
     */
    public function create()
    {
        return view('pages.prospects.create');
    }

    /**
     * STORE DATA
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|unique:prospects,phone',
            'address' => 'nullable|string',
            'source' => 'nullable|string',
            'interest_of' => 'nullable|string',
            'status' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
            'follow_up_at' => 'nullable|date',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = $validated['status'] ?? 'new';

        Prospect::create($validated);

        return redirect()
            ->route('prospects.index')
            ->with('success', 'Prospect berhasil ditambahkan');
    }

    /**
     * FORM EDIT
     */
    public function edit(Prospect $prospect)
    {
        // keamanan: hanya pemilik data
        abort_if($prospect->user_id !== auth()->id(), 403);

        return view('pages.prospects.edit', compact('prospect'));
    }

    /**
     * UPDATE DATA
     */
    public function update(Request $request, Prospect $prospect)
    {
        abort_if($prospect->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|unique:prospects,phone,' . $prospect->id,
            'address' => 'nullable|string',
            'source' => 'nullable|string',
            'interest_of' => 'nullable|string',
            'status' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
            'follow_up_at' => 'nullable|date',
        ]);

        $prospect->update($validated);

        return redirect()
            ->route('prospects.index')
            ->with('success', 'Prospect berhasil diupdate');
    }

    /**
     * DELETE DATA
     */
    public function destroy(Prospect $prospect)
    {
        abort_if($prospect->user_id !== auth()->id(), 403);

        $prospect->delete();

        return back()->with('success', 'Prospect berhasil dihapus');
    }
}