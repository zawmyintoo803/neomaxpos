<table class="mdc-data-table mdc-elevation--z2" style="width:100%; overflow-x:auto;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Section</th>
            <th>Price</th>
            <th>Old Price</th>
            <th>Badge</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td><img src="{{ $p->images->first()->image_url ?? 'https://via.placeholder.com/60' }}" style="width:60px;height:60px;border-radius:6px;"></td>
            <td>{{ $p->name }}</td>
            <td>{{ $p->category->name ?? '-' }}</td>
            <td>{{ $p->section->name ?? '-' }}</td>
            <td>Ks {{ number_format($p->price) }}</td>
            <td>{{ $p->old_price ? 'Ks '.number_format($p->old_price) : '-' }}</td>
            <td>{{ $p->badge ?? '-' }}</td>
            <td>
                <div style="display:flex;gap:6px;">
                    <a href="{{ route('admin.products.edit', $p->id) }}" class="mdc-button mdc-button--raised">Edit</a>
                    <form method="POST" action="{{ route('admin.products.destroy', $p->id) }}" onsubmit="return confirm('Delete this product?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="mdc-button mdc-button--outlined" style="color:red;border-color:red;">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="text-align:center;">No products found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $products->links() }}
