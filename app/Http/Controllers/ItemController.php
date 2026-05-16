<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of items.
     */
    public function index(Request $request)
    {
        $query = Item::with('category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('sku', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('stock_available', '>', 0)->where('is_active', true);
            } elseif ($request->availability === 'unavailable') {
                $query->where(function ($q) {
                    $q->where('stock_available', 0)->orWhere('is_active', false);
                });
            }
        }

        $items = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('items.index', compact('items', 'categories'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created item.
     */
    public function store(ItemRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('items', 'public');
        }

        $data['stock_available'] = $data['stock_total'];

        Item::create($data);

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Display the specified item.
     */
    public function show(Item $item)
    {
        $item->load('category');

        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing an item.
     */
    public function edit(Item $item)
    {
        $categories = Category::orderBy('name')->get();

        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update an existing item.
     */
    public function update(ItemRequest $request, Item $item)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($item->photo_path) {
                Storage::disk('public')->delete($item->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('items', 'public');
        }

        // Adjust stock_available if stock_total changed
        if (isset($data['stock_total']) && $data['stock_total'] != $item->stock_total) {
            $diff = $data['stock_total'] - $item->stock_total;
            $data['stock_available'] = max(0, $item->stock_available + $diff);
        }

        $item->update($data);

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Remove an item.
     */
    public function destroy(Item $item)
    {
        if ($item->rentalItems()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus barang yang pernah disewa.');
        }

        if ($item->photo_path) {
            Storage::disk('public')->delete($item->photo_path);
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
