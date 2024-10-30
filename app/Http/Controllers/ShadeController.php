<?php

namespace App\Http\Controllers;

use App\Models\Shade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShadeController extends Controller
{
    // Index method for the dashboard
    public function index()
    {
        return Inertia::render('Dashboard');  // Render the simple dashboard view
    }

    // Method to render the Shades of Pink page
    public function shadesOfPink()
    {
        $shades = Shade::with('user')->get()->map(function ($shade) {
            return [
                'id' => $shade->id,
                'name' => $shade->name,
                'color_code' => $shade->color_code,
                'created_at' => $shade->created_at->format('Y-m-d'),
                'updated_at' => $shade->updated_at && $shade->updated_at != $shade->created_at ? $shade->updated_at->format('Y-m-d H:i:s') : null,
                'created_by' => $shade->user ? $shade->user->name : 'Unknown',
            ];
        });
    
        return Inertia::render('ShadesofPink', [
            'shades' => $shades,
        ]);
    }

    // Method to store a new shade
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:shades,name',
            'color_code' => 'required|string|max:7|unique:shades,color_code',
        ]);

        Shade::create([
            'name' => $request->name,
            'color_code' => $request->color_code,
            'created_by' => auth()->user()->id,
        ]);

        return redirect()->route('shades.index');  // Redirect to shades index after storing
    }

    // Method to update an existing shade
    public function update(Request $request, Shade $shade)
    {


        $shade->update($request->only(['name', 'color_code']));

        return redirect()->route('shades.index');  // Redirect to shades index after update
    }

    // Method to delete a shade
    public function destroy(Shade $shade)
    {
        $shade->delete();

        return redirect()->route('shades.index');  // Redirect to shades index after delete
    }
}
