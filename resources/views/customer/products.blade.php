@extends('layouts.app')

@section('content')
<h2 class="mdc-typography--headline5">Products</h2>

<div class="card-grid">
@foreach($products as $product)
  <div class="mdc-card">
    <img src="{{ asset($product->image ?? 'images/product1.jpg') }}" alt="{{ $product->name }}">
    <div class="mdc-card__primary-action" tabindex="0" style="padding:16px;">
      <h3 class="mdc-typography--headline6">{{ $product->name }}</h3>
      <p class="mdc-typography--body2">{{ $product->description }}</p>
      <p class="mdc-typography--subtitle1" style="color:#d50000; font-weight:bold;">Ks {{ $product->price }}</p>
    </div>
    <div class="mdc-card__actions" style="padding:16px;">
      <button onclick="alert('Added {{ $product->name }} to cart')" class="btn btn-blue" style="width:100%;">Add to Cart</button>
    </div>
  </div>
@endforeach
</div>
@endsection
