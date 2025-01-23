@extends('layouts.admin')

@section('title', 'GoBlood | Manage Educational Resources')

@section('header')
    <h2 class="font-semibold text-xl text-black leading-tight">
        {{ __('Educational Resources') }}
    </h2>
@endsection  

@section('content')

@if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <!-- Button to add a new educational resource -->
    <a href="{{ route('blood_bank_admin.educations.create') }}" class="btn btn-primary mb-3">Add New Educational Resource</a>

    <!-- Educational Resources Card -->
    <div class="card mb-4">
        <div class="card-header text-black">
            <h3 class="m-0 py-2">Educational Resources</h3>
        </div>

        @if($educations->isEmpty())
            <p class="p-3">No educational resources available.</p>
        @else
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Thumbnail</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($educations as $education)
                            <tr>
                                <td>{{ $education->title }}</td>
                                <td>
                                    @if($education->thumbnail)
                                        <img src="{{ asset('storage/' . $education->thumbnail) }}" alt="Thumbnail" width="50">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>{{ $education->created_at->format('d-m-Y') }}</td>
                                <td>
                                    <a href="{{ route('blood_bank_admin.educations.edit', $education->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('blood_bank_admin.educations.destroy', $education->id) }}" method="POST" style="display:inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-button">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-button');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form'); // Find the closest form element

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to undo this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit the form if confirmed
                    }
                });
            });
        });
    });
</script>
@endsection
