<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $positions = Position::paginate(10);
        return view('admin.positions.index', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.positions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Sanitize currency inputs (remove dots)
        $request->merge([
            'basic_salary' => str_replace('.', '', $request->input('basic_salary', '0')),
            'meal_allowance' => str_replace('.', '', $request->input('meal_allowance', '0')),
            'absent_fee' => str_replace('.', '', $request->input('absent_fee', '0')),
        ]);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:positions,name',
            'basic_salary' => 'required|numeric|min:0',
            'meal_allowance' => 'required|numeric|min:0',
            'absent_fee' => 'required|numeric|min:0',
        ]);

        Position::create($validated);

        return redirect()->route('admin.positions.index')
            ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        return view('admin.positions.edit', compact('position'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Position $position)
    {
        // Sanitize currency inputs (remove dots)
        $request->merge([
            'basic_salary' => str_replace('.', '', $request->input('basic_salary', '0')),
            'meal_allowance' => str_replace('.', '', $request->input('meal_allowance', '0')),
            'absent_fee' => str_replace('.', '', $request->input('absent_fee', '0')),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:positions,name,' . $position->id,
            'basic_salary' => 'required|numeric|min:0',
            'meal_allowance' => 'required|numeric|min:0',
            'absent_fee' => 'required|numeric|min:0',
        ]);

        $position->update($validated);

        return redirect()->route('admin.positions.index')
            ->with('success', 'Jabatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->route('admin.positions.index')
            ->with('success', 'Jabatan berhasil dihapus.');
    }
}
