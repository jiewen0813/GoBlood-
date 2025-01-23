@extends('layouts.admin')

@section('title', 'GoBlood | Blood Inventory')

@section('header')
    <h2 class="font-semibold text-xl text-black leading-tight">
        {{ __('Blood Inventory') }}
    </h2>
@endsection  

@section('content')

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if($inventories->isEmpty())
        <div class="card-body">
            <p class="text-center">No inventory records available.</p>
        </div>
    @else
        <div class="row">
            @foreach($inventories as $inventory)
                <div class="col-md-3 mb-4">
                    <div class="card text-center shadow">
                        <div class="card-body">
                            <!-- Blood Drop SVG -->
                            <div class="blood-drop-container">
                                <svg width="60" height="90" viewBox="0 0 30 42">
                                    <path fill="transparent" stroke="#000" stroke-width="1.5"
                                          d="M15 3
                                             Q16.5 6.8 25 18
                                             A12.8 12.8 0 1 1 5 18
                                             Q13.5 6.8 15 3z" />
                                    <defs>
                                        <clipPath id="blood-drop-clip">
                                            <path d="M15 3
                                                     Q16.5 6.8 25 18
                                                     A12.8 12.8 0 1 1 5 18
                                                     Q13.5 6.8 15 3z" />
                                        </clipPath>
                                    </defs>
                                    <rect x="0" y="{{ 42 - ($inventory->quantity / 100) * 42 }}" width="30" 
                                          height="{{ ($inventory->quantity / 100) * 42 }}" 
                                          fill="red" clip-path="url(#blood-drop-clip)" class="blood-fill" />
                                </svg>
                            </div>

                            <!-- Blood Group -->
                            <h4 class="mt-3">{{ $inventory->blood_type }}</h4>

                            <!-- Quantity -->
                            <p>{{ $inventory->quantity }} units</p>

                            <!-- Edit Button -->
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $inventory->id }}">
                                Edit
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $inventory->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $inventory->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('blood_bank_admin.inventories.update', $inventory->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $inventory->id }}">Edit {{ $inventory->blood_type }} Stock</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="action{{ $inventory->id }}">Action</label>
                                        <select name="action" id="action{{ $inventory->id }}" class="form-control" required>
                                            <option value="" disabled selected>Select Action</option>
                                            <option value="increment">Increase Stock</option>
                                            <option value="decrement">Decrease Stock</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="quantity{{ $inventory->id }}">Quantity (units)</label>
                                        <input type="number" name="quantity" id="quantity{{ $inventory->id }}" class="form-control" min="0" required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
