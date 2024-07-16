<!-- resources/views/admin/remove-password.blade.php -->
@extends('admin_layout')
@section('content_dash')

<div class="container">
    <h4>Remove User Password</h4>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.removePassword') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="customer_id">Select Customer:</label>
            <select id="customer_id" name="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->idCustomer }}">{{ $customer->username }} ({{ $customer->email }})</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-danger">Remove Password</button>
    </form>
</div>

@endsection
