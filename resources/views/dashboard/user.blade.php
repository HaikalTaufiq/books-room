@extends('layouts.app')

@section('title', 'User Data')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard/user.css') }}">
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this user?");
    }

    function updateStatus(id, selectElement) {
        const status = selectElement.value;

        fetch(`/users/${id}`, {
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
<div class="status-page">
    <h1 class="status-title">User Data</h1>
    <div class="header-actions">
        <a href="{{ route('register.form') }}" class="add-user-btn">+ User</a>
        <form action="{{ route('users.index') }}" method="GET">
            <input
                type="text"
                name="search"
                placeholder="Search..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>

        </form>
    </div>
    <div class="table-wrapper">
        <table class="status-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="status {{ strtolower($user->role) }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        <button
                            class="edit-btn"
                            data-id="{{ $user->id }}"
                            data-first_name="{{ $user->first_name }}"
                            data-last_name="{{ $user->last_name }}"
                            data-phone="{{ $user->phone }}"
                            data-email="{{ $user->email }}"
                            data-role="{{ $user->role }}"
                            onclick="handleEditClick(this)"
                            style="background: none; border: none;">
                            <i class="fas fa-edit"></i>
                        </button>

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none;border:none; color: red; margin-left: 10px">
                                <i class="fas fa-trash text-danger fa-lg"></i>
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $users->withQueryString()->links() }}
    </div>

</div>

<!-- Modal Edit User -->
<div class="modal-overlay" id="editModal" style="display: none;">
    <div class="modal-container">
        <h2 style="font-size: large;">Edit User</h2>
        <form method="POST" action="{{ route('users.update') }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="edit-id">

            <input type="text" name="first_name" id="edit-first-name" class="modal-input" placeholder="First Name">
            <input type="text" name="last_name" id="edit-last-name" class="modal-input" placeholder="Last Name">
            <input type="text" name="phone" id="edit-phone" class="modal-input" placeholder="Phone">
            <input type="email" name="email" id="edit-email" class="modal-input" placeholder="Email">
            <select name="role" id="edit-role" class="modal-input">
                <option value="admin">Admin</option>
                <option value="employee">Employee</option>
            </select>
            <div class="modal-buttons">
                <button type="submit" class="modal-save">Save</button>
                <button type="button" class="modal-close" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, firstName, lastName, phone, email, role) {
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-first-name').value = firstName;
        document.getElementById('edit-last-name').value = lastName;
        document.getElementById('edit-phone').value = phone;
        document.getElementById('edit-email').value = email;
        document.getElementById('edit-role').value = role.toLowerCase();
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    // Optional: Tutup modal kalau klik di luar kontainer
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target == modal) {
            closeEditModal();
        }
    }

    function handleEditClick(button) {
        const id = button.dataset.id;
        const firstName = button.dataset.first_name;
        const lastName = button.dataset.last_name;
        const phone = button.dataset.phone;
        const email = button.dataset.email;
        const role = button.dataset.role;

        openEditModal(id, firstName, lastName, phone, email, role);
    }


    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const userId = this.getAttribute('data-id');

                if (confirm('Yakin ingin menghapus user ini?')) {
                    // Buat form delete dan submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/users/${userId}`;

                    // Tambahkan CSRF token
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    // Tambahkan method spoofing
                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endsection