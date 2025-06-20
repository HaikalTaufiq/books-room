@extends('layouts.app')

@section('title', 'Room Management')

@section('content')
<link href="{{ asset('css/custom/index.css') }}" rel="stylesheet">

@section('script')
<script src="{{ asset('js/rooms/manage.js') }}"></script>
<script>
    function confirmDelete() {
        return confirm("Apakah Anda yakin ingin menghapus peminjaman ini?");
    }

    function updateStatus(id, selectElement) {
        const status = selectElement.value;

        fetch(`/rooms/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
            })
            .catch(error => {
                console.error('Update error:', error);
            });
    }
</script>
@endsection

<div class="container">
    <h1 class="page-title">Room Management</h1>

    <!-- Tombol Tambah Ruangan -->
    <div class="header-actions">
        <button class="btn-add-room" id="openModalBtn">+ Room</button>

        <form action="{{ route('rooms.index') }}" method="GET">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>




    <!-- Modal Tambah Ruangan -->
    <div id="addRoomModal" class="custom-modal hidden" role="dialog" aria-modal="true" aria-labelledby="addRoomModalLabel">
        <div class="custom-modal-content">
            <button class="close-modal" id="closeModalBtn" aria-label="Close modal">&times;</button>
            <form action="{{ route('rooms.store') }}" method="POST" class="form-modal">
                @csrf
                <h2 id="addRoomModalLabel">Add New Room</h2>

                <label for="name">Room Name</label>
                <input type="text" id="name" name="name" required>

                <label for="location">Location</label>
                <input type="text" id="location" name="location" required>

                <label for="capacity">Capacity</label>
                <input type="number" id="capacity" name="capacity" required>

                <label for="available">Available?</label>
                <select id="available" name="available" required>
                    <option value="1">Available</option>
                    <option value="0">Not Available</option>
                </select>

                <div class="modal-footer">
                    <button type="button" id="cancelBtn" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Ruangan -->
    <div class="table-wrapper">
        @if($rooms->isEmpty())
        <p>No Room Available.</p>
        @else
        <table class="custom-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Room Name</th>
                    <th>Location</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $room->name }}</td>
                    <td>{{ $room->location }}</td>
                    <td>{{ $room->capacity }} People</td>
                    <td>{{ $room->available ? 'Available' : 'Not Available' }}</td>
                    <td>
                        <a href="#"
                            class="edit-btn"
                            title="Edit"
                            data-id="{{ $room->id }}"
                            data-name="{{ $room->name }}"
                            data-location="{{ $room->location }}"
                            data-capacity="{{ $room->capacity }}"
                            data-available="{{ $room->available }}"
                            data-bs-toggle="modal"
                            data-bs-target="#editRoomModal">
                            <i class="fas fa-edit" style="color:blue;"></i>
                        </a>

                        <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; color: red;">
                                <i class="fas fa-trash text-danger"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @endif
    </div>


    <div class="d-flex justify-content-center mt-4">
        {{ $rooms->appends(['search' => request('search')])->links() }}


    </div>
</div>

<!-- Form Edit Ruangan -->

<div id="editDialog" class="edit-dialog">

    <form id="editRoomForm" method="POST" class="edit-form">
        @csrf
        @method('PUT')
        <h5 style="font-size: 24px;">Edit Room</h5>
        <input type="hidden" name="id" id="editRoomId" />

        <label for="editName">Room Name</label>
        <input type="text" id="editName" name="name" required>

        <label for="editLocation">Location</label>
        <input type="text" id="editLocation" name="location" required>

        <label for="editCapacity">Capacity</label>
        <input type="number" id="editCapacity" name="capacity" required>

        <label for="editAvailable">Available?</label>
        <select id="editAvailable" name="available" required>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>

        <div class="edit-dialog-buttons">
            <button type="button" id="editCancelBtn">Cancel</button>
            <button type="submit">Update</button>
        </div>

    </form>
</div>


@endsection