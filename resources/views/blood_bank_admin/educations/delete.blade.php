@extends('layouts.admin')

@section('title', 'GoBlood | Delete Educational Resource')

@section('header')
    <h2 class="font-semibold text-xl text-black leading-tight">
        {{ __('Delete Educational Resource') }}
    </h2>
@endsection

@section('content')
    <!-- Confirmation Section -->
    <div class="card">
        <div class="card-header">
            <h5>Are you sure you want to delete this educational resource?</h5>
        </div>
        <div class="card-body">
            <!-- Displaying resource information -->
            <div class="form-group">
                <label><strong>Title:</strong></label>
                <p>{{ $education->title }}</p>
            </div>

            @if($education->thumbnail)
                <div class="form-group">
                    <label><strong>Thumbnail:</strong></label>
                    <img src="{{ asset('storage/' . $education->thumbnail) }}" alt="Thumbnail" width="100">
                </div>
            @else
                <div class="form-group">
                    <label><strong>Thumbnail:</strong></label>
                    <p>No Image</p>
                </div>
            @endif

            <div class="form-group mt-3">
                <form action="{{ route('blood_bank_admin.educations.destroy', $education->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this resource?')">Yes, Delete</button>
                    <a href="{{ route('blood_bank_admin.educations.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
