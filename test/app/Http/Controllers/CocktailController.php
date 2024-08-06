<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cocktail;

class CocktailController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'idDrink' => 'required|unique:cocktails',
            'name' => 'required',
            'image_url' => 'nullable|url',
            'category' => 'nullable',
            'alcoholic' => 'nullable',
        ]);

        Cocktail::updateOrCreate(
            ['idDrink' => $request->idDrink],
            [
                'name' => $request->name,
                'image_url' => $request->image_url,
                'category' => $request->category,
                'alcoholic' => $request->alcoholic,
            ]
        );

        return response()->json(['message' => 'Cocktail saved!'], 200);
    }

    public function index()
    {
        $cocktails = Cocktail::all();
        return response()->json($cocktails);
    }

    public function show(Cocktail $cocktail)
    {
        return response()->json($cocktail);
    }

    public function update(Request $request, Cocktail $cocktail)
    {
        $request->validate([
            'name' => 'required',
            'image_url' => 'nullable|url',
            'category' => 'nullable',
            'alcoholic' => 'nullable',
        ]);

        $cocktail->update($request->only([
            'name',
            'image_url',
            'category',
            'alcoholic',
        ]));

        return response()->json(['message' => 'Cocktail updated!'], 200);
    }

    public function destroy(Cocktail $cocktail)
    {
        $cocktail->delete();
        return response()->json(['message' => 'Cocktail deleted!'], 200);
    }
}
