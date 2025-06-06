@extends('admin.admin_master')

@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <a href="{{ route('staffs.index') }}" class="btn btn-warning btn-rounded mb-3" style="margin-top: 10px;">
            <i class="fas fa-arrow-left"></i> Back to Staff List
        </a>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Edit Staff</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('staffs.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Staff Name</label>
                                <input type="text" name="staffName" class="form-control" value="{{ old('staffName', $staff->staffName) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="staffPhone" class="form-control" value="{{ old('staffPhone', $staff->staffPhone) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="staffEmail" class="form-control" value="{{ old('staffEmail', $staff->staffEmail) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="staff_Address" class="form-control" value="{{ old('staff_Address', $staff->staff_Address) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" name="department" class="form-control" value="{{ old('department', $staff->department ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="join_date" class="form-label">Join Date</label>
                                <input type="date" name="join_date" class="form-control" value="{{ old('join_date', $staff->join_date) }}">
                            </div>



                            <div class="mb-3">
                                <label class="form-label">Salary</label>
                                <input type="text" name="staffSalary" class="form-control" value="{{ old('staffSalary', $staff->staffSalary) }}" required>
                            </div>

                            {{-- Existing document previews --}}
                            @php
                            $citizenship = $staff->documents->firstWhere('type', 'Citizenship');
                            $pancard = $staff->documents->firstWhere('type', 'PAN Card');
                            $others = $staff->documents->where('type', 'Other');
                            @endphp

                            <div class="mb-3">
                                <label class="form-label">Citizenship (upload new to replace)</label>
                                <input type="file" name="citizenship" class="form-control" accept="image/*,application/pdf">
                                @if ($citizenship)
                                <small>Current: <a href="{{ asset('storage/' . $citizenship->filepath) }}" target="_blank">{{ $citizenship->filename }}</a></small>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">PAN Card (upload new to replace)</label>
                                <input type="file" name="pancard" class="form-control" accept="image/*,application/pdf">
                                @if ($pancard)
                                <small>Current: <a href="{{ asset('storage/' . $pancard->filepath) }}" target="_blank">{{ $pancard->filename }}</a></small>
                                @endif
                            </div>

                            {{-- Dynamic other documents --}}
                            <div class="mb-3">
                                <label class="form-label">Other Documents (upload more)</label>
                                <div id="extraDocuments"></div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addDocument()">+ Add More</button>
                            </div>

                            @if ($others->count())
                            <div class="mb-3">
                                <label class="form-label">Existing Other Documents:</label>
                                <ul>
                                    @foreach ($others as $doc)
                                    <li>
                                        <a href="{{ asset('storage/' . $doc->filepath) }}" target="_blank">{{ $doc->filename }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <button type="submit" class="btn btn-primary">Update Staff</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    let docCount = 0;

    function addDocument() {
        docCount++;
        const container = document.getElementById('extraDocuments');
        const html = `
            <div class="mb-2" id="doc-${docCount}">
                <input type="file" name="other_documents[]" class="form-control" accept="image/*,application/pdf">
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }
</script>

@endsection