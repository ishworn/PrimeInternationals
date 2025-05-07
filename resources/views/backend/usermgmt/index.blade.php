@extends('admin.admin_master')

@section('admin')
    <div class="page-content">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div style="display: flex; justify-content: flex-end;">
            <a href="#" class="btn btn-warning btn-rounded no-print" data-bs-toggle="modal" data-bs-target="#addUserModal"
                style="font-size: 15px; display: inline-flex; align-items: center; text-decoration: none;
              background-color: #2e9dec; color: white; padding: 10px 10px; border-radius: 5px;
              margin-bottom: 15px; margin-top: 5px; margin-left: 20px;">
                <i class="fa fa-plus" aria-hidden="true" style="margin-right: 5px"></i>
                Add User
            </a>
        </div>

        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addUserForm" action="{{ route('usermgmt.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <!-- Trackings Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h4 class="card-title mb-4" style="font-size: 22px; font-weight: bold;">User Management</h4>
                            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>User Name</th>
                                        <th>Gmail</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <form action="{{ route('usermgmt.destroy', $user->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn bg-none">
                                                        <i class="fa fa-trash" aria-hidden="true"
                                                            style="color: rgb(247, 37, 37)"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add some custom styles for modern design -->
    <style>
        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .card {
            border-radius: 8px;
            overflow: hidden;
        }

        table th,
        table td {
            text-align: center;
            padding: 12px;
            font-size: 16px;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        table th {
            background-color: #3e8e41;
            color: white;
            text-align: center;
        }

        table td {
            vertical-align: middle;
        }

        .modal-content {
            border-radius: 8px;
        }
    </style>
@endsection
