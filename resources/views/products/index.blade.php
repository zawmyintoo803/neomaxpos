@extends('admin.layouts.app')

@section('title', 'Admin Products')

@section('content')

<h1 class="mdc-typography--headline5" style="margin-bottom: 25px;">Products Table</h1>

<style>
    .mdc-data-table {
        width: 100%;
        overflow-x: auto;
    }
    table th, table td {
        text-align: left;
        padding: 12px 16px;
    }
    table img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
    }
    .action-buttons {
        display: flex;
        gap: 8px;
    }
</style>

<div class="mdc-data-table mdc-elevation--z2">
    <table class="mdc-data-table__table" aria-label="Products Table">
        <thead>
            <tr class="mdc-data-table__header-row">
                <th class="mdc-data-table__header-cell">ID</th>
                <th class="mdc-data-table__header-cell">Image</th>
                <th class="mdc-data-table__header-cell">Name</th>
                <th class="mdc-data-table__header-cell">Category</th>
                <th class="mdc-data-table__header-cell">Section</th>
                <th class="mdc-data-table__header-cell">Price</th>
                <th class="mdc-data-table__header-cell">Old Price</th>
                <th class="mdc-data-table__header-cell">Badge</th>
                <th class="mdc-data-table__header-cell">Actions</th>
            </tr>
        </thead>

        <tbody class="mdc-data-table__content">
            @foreach($products as $p)
                <tr class="mdc-data-table__row">
                    <td class="mdc-data-table__cell">{{ $p->id }}</td>

                    <td class="mdc-data-table__cell">
                        <img src="{{ $p->images->first()->image_url ?? 'https://via.placeholder.com/60' }}">
                    </td>

                    <td class="mdc-data-table__cell">{{ $p->name }}</td>

                    <td class="mdc-data-table__cell">
                        {{ $p->category->name ?? '-' }}
                    </td>

                    <td class="mdc-data-table__cell">
                        {{ $p->section->name ?? '-' }}
                    </td>

                    <td class="mdc-data-table__cell">
                        Ks {{ number_format($p->price) }}
                    </td>

                    <td class="mdc-data-table__cell">
                        {{ $p->old_price ? 'Ks ' . number_format($p->old_price) : '-' }}
                    </td>

                    <td class="mdc-data-table__cell">
                        <span style="background:#eee;padding:4px 8px;border-radius:4px;">
                            {{ $p->badge ?? '-' }}
                        </span>
                    </td>

                    <td class="mdc-data-table__cell">
                        <div class="action-buttons">

                            <!-- Edit Button -->
                            <a href="{{ route('admin.products.edit', $p->id) }}"
                               class="mdc-button mdc-button--raised">
                                <span class="mdc-button__label">Edit</span>
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.products.destroy', $p->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this product?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="mdc-button mdc-button--outlined"
                                        style="color:red;border-color:red;">
                                    <span class="mdc-button__label">Delete</span>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
