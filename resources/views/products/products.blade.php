@extends('layouts.app')

@section('content')
<h2 class="mdc-typography--headline5">Admin Products</h2>

<a href="{{ route('admin.products.create') }}" class="btn btn-blue" style="margin:10px 0; display:inline-block;">Add New Product</a>

<div class="card-grid">
@foreach($products as $product)
  <div class="mdc-card">
    <img src="{{ asset($product->image ?? 'images/product1.jpg') }}" alt="{{ $product->name }}">
    <div class="mdc-card__primary-action" tabindex="0" style="padding: 16px;">
      <h3 class="mdc-typography--headline6">{{ $product->name }}</h3>
      <p class="mdc-typography--body2">{{ $product->description }}</p>
      <p class="mdc-typography--subtitle1" style="color:#d50000; font-weight:bold;">Ks {{ $product->price }}</p>
    </div>
    <div class="mdc-card__actions" style="padding: 16px; display:flex; gap:10px;">
      <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-green">Edit</a>
      <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button class="btn btn-red">Delete</button>
      </form>
    </div>
  </div>
@endforeach
</div>
@endsection
