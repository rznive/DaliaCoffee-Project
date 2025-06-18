<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use App\Models\ingredients;
use App\Models\menu_ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function katalog()
    {
        $menus = Menu::where('status', 'active')->with('category')->get();
        return view('katalog', compact('menus'));
    }
    public function index(Request $request)
    {
        $menus = Menu::with('category', 'menuIngredients.ingredient')->get();
        $categories = Category::all();
         $allIngredients =  ingredients::all();
        return view('menu.index', compact('menus', 'categories', 'allIngredients'));
    }

    /**
     * Form tambah menu baru.
     */
    public function create()
    {
        $categories = Category::all();
        $allIngredients =  ingredients::all();
        return view('menu.create', compact('categories', 'allIngredients'));
    }

    /**
     * Simpan menu baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:1',
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('menu_images', 'public')
            : null;

        $menu = Menu::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'status' => $request->status,
            'image' => $imagePath,
        ]);

        foreach ($request->ingredients as $ingredient) {
            menu_ingredient::create([
                'menu_id' => $menu->id,
                'ingredient_id' => $ingredient['ingredient_id'],
                'quantity' => $ingredient['quantity'],
            ]);
        }

        return redirect()->route('menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }


    /**
     * Tampilkan detail 1 menu.
     */
    public function show(Menu $menu)
    {
        $menu->load('category', 'menuIngredients.ingredient');

        return view('menu.show', compact('menu'));
    }

    /**
     * Form edit menu.
     */
    public function edit(Menu $menu)
    {
        $categories = Category::all();
        $allIngredients = ingredients::all(); // Semua bahan tersedia
        $menuIngredients = $menu->menuIngredients()->with('ingredient')->get(); // Bahan yang sudah ditambahkan ke menu ini

        return view('menu.edit', compact('menu', 'categories', 'allIngredients', 'menuIngredients'));
    }

    /**
     * Update data menu.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:1',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }

            $menu->image = $request->file('image')->store('menu_images', 'public');
        }

        $menu->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'status' => $request->status,
            'image' => $menu->image,
        ]);

        $menu->menuIngredients()->delete();

        foreach ($request->ingredients as $ingredient) {
            menu_ingredient::create([
                'menu_id' => $menu->id,
                'ingredient_id' => $ingredient['ingredient_id'],
                'quantity' => $ingredient['quantity'],
            ]);
        }

        return redirect()->route('menu.index')->with('success', 'Menu berhasil diupdate.');
    }

    /**
     * Hapus menu.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus.');
    }
}
