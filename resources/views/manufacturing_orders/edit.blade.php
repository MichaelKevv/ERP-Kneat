@extends('template')

@section('content')
    <div class="container">
        <h1>Edit Manufacturing Order</h1>
        <form action="{{ route('manufacturing_orders.update', $manufacturingOrder->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="product_id">Product</label>
                <select name="product_id" id="product_id" class="form-control" required>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                            {{ $manufacturingOrder->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="1" required
                    value="{{ $manufacturingOrder->quantity }}">
            </div>

            <div class="form-group">
                <label for="production_date">Production Date</label>
                <input type="date" name="production_date" id="production_date" class="form-control" required
                    value="{{ $manufacturingOrder->production_date }}">
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="draft" {{ $manufacturingOrder->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="confirmed" {{ $manufacturingOrder->status == 'confirmed' ? 'selected' : '' }}>Confirmed
                    </option>
                    <option value="in_progress" {{ $manufacturingOrder->status == 'in_progress' ? 'selected' : '' }}>In
                        Progress</option>
                    <option value="done" {{ $manufacturingOrder->status == 'done' ? 'selected' : '' }}>Done</option>
                    <option value="canceled" {{ $manufacturingOrder->status == 'canceled' ? 'selected' : '' }}>Canceled
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-success mt-3">Update</button>
        </form>
    </div>
@endsection
