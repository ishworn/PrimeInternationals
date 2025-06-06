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
                        <h4 class="mb-0">Add New Staff</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('staffs.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="staffName" class="form-label">Staff Name</label>
                                <input type="text" name="staffName" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="staffPhone" class="form-label">Phone</label>
                                <input type="text" name="staffPhone" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="staffEmail" class="form-label">Email</label>
                                <input type="email" name="staffEmail" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="staff_Address" class="form-label">Address</label>
                                <input type="text" name="staff_Address" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" name="department" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="join_date" class="form-label">Join Date</label>
                                <input type="date" name="join_date" class="form-control" required>
                            </div>



                            <div class="mb-3">
                                <label for="staffSalary" class="form-label">Salary</label>
                                <input type="text" name="staffSalary" class="form-control" required>
                            </div>


                            <!-- <div class="mb-3">
                                <label for="file" class="form-label">Upload File (Image or PDF)</label>
                                <input type="file" name="document" class="form-control" accept="image/*,application/pdf" required>

                            </div> -->
                            <!-- Fixed document inputs -->
                            <div class="mb-3">
                                <label class="form-label">Citizenship</label>
                                <input type="file" name="citizenship" class="form-control" accept="image/*,application/pdf" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">PAN Card</label>
                                <input type="file" name="pancard" class="form-control" accept="image/*,application/pdf" required>
                            </div>

                            <!-- Dynamic extra documents -->
                            <div id="extraDocuments"></div>
                            <button type="button" class="btn btn-sm btn-outline-secondary mb-3" onclick="addDocument()">+ Add More Documents</button>

                            <button type="submit" class="btn btn-success">Save Staff</button>
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
            <div class="mb-3" id="doc-${docCount}">
                <label class="form-label">Other Document ${docCount}</label>
                <input type="file" name="other_documents[]" class="form-control" accept="image/*,application/pdf">
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }
</script>


@endsection