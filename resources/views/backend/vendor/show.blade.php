@extends('admin.admin_master')
@section('admin')

<div class="container" style="margin-top: 6rem;">
    <h4 class="mb-4 font-weight-bold">Vendors and Total Senders</h4>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark bg-primary text-white text-center">
                        <tr>
                            <th>#</th>
                            <th>Vendor Name</th>
                            <th>Email</th>
                            <th>Total Senders</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $index => $vendor)
                        <tr class="text-center">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $vendor->name }}</td>
                            <td>{{ $vendor->email }}</td>
                            <td>{{ $vendor->senders_count }}</td>

                            <td class="text-center">
                                <a href="{{ route('vendor.index', ['id' => $vendor->id]) }}" title="Status"
                                    class="btn btn-dark btn-sm"
                                    style="margin-right: 5px;">

                                    <i class="fas fa-users"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No vendors found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection