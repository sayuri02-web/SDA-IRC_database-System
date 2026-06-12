<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Church;
use App\Models\ActivityLog;

class ChurchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $cluster = $request->cluster;

        $churches = Church::withCount('members')

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('church_name', 'LIKE', "%{$search}%")
                    ->orWhere('cluster', 'LIKE', "%{$search}%");

                });

            })

            ->when($cluster, function ($query) use ($cluster) {

                $query->where('cluster', $cluster);

            })

            ->get();

        return view('church.index', compact('churches'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('church.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Church::create([

            'church_name' => $request->church_name,
            'cluster' => $request->cluster,

            // ✅ ADDRESS FIELDS
            'region' => $request->region,
            'province' => $request->province,
            'city' => $request->city,
            'barangay' => $request->barangay,
            'street' => $request->street,

        ]);

        ActivityLog::log('Church Registration', 'Created', 'Registered new church: ' . $request->church_name);

        return redirect()
            ->route('church.index')
            ->with('success', 'Church Added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $church = Church::findOrFail($id);

        return view('church.edit', compact('church'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $church = Church::findOrFail($id);

        $church->update([

        'church_name' => $request->church_name,
        'cluster'     => $request->cluster,
    
        'region'      => $request->region,
        'province'    => $request->province,
        'city'        => $request->city,
        'barangay'    => $request->barangay,
        'street'      => $request->street,

        ]);

        ActivityLog::log('Church Registration', 'Updated', 'Updated church: ' . $request->church_name, $church->id);

        return redirect('/church')
                ->with('success', 'Church updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $church = Church::findOrFail($id);

        $church->delete();

        ActivityLog::log('Church Registration', 'Deleted', 'Deleted church: ' . $church->church_name, (int) $id);

        return redirect('/church')
                ->with('success', 'Church deleted successfully.');
    }
}