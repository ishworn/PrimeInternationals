<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class StaffController extends Controller
{

    public function index()
    {
        $staffs = Staff::with('documents')->get();
        $totalStaffs = $staffs->count();

        $totalSalary = $staffs->sum(function ($staff) {
            return (float) $staff->staffSalary;
        });

        $totalDepartments = Staff::whereNotNull('department')
            ->distinct('department')
            ->count('department');

        foreach ($staffs as $staff) {
            $joinDate = Carbon::parse($staff->join_date);
            $now = Carbon::now();

            $salaryDay = $joinDate->day;

            // First eligible salary date is one month after join date, same day of month
            // We use startOfMonth() + (salaryDay - 1) days to safely handle edge cases like 31st on short months
            $firstSalaryDate = $joinDate->copy()->addMonth()->startOfMonth()->addDays($salaryDay - 1);

            // Calculate next salary date (which must be today or later)
            $nextSalaryDate = $firstSalaryDate->copy();
            while ($nextSalaryDate->lt($now)) {
                $nextSalaryDate->addMonth();
            }

            // Calculate difference in days, allowing negative (false)
            $diffInDays = $now->diffInDays($nextSalaryDate, false);

            if ($diffInDays < 0) {
                $ssalaryStatus = abs($diffInDays) . ' days ago';
            } elseif ($diffInDays === 0) {
                $salaryStatus = 'Today';
            } elseif ($diffInDays <= 7) {
                $salaryStatus = $diffInDays . ' days left';
            } else {
                $salaryStatus = null; // More than 7 days away, show nothing
            }
        }

        return view('backend.staff.index', compact('staffs', 'totalStaffs', 'totalSalary', 'totalDepartments','salaryStatus'));
    }






    public function create()
    {
        return view('backend.staff.create');
    }



    public function store(Request $request)
    {
        $request->validate([
            'staffName' => 'required|string',
            'staffPhone' => 'required|string',
            'staffEmail' => 'required|email',
            'staff_Address' => 'required|string',
            'staffSalary' => 'required|string',
            'department' => 'required|string',
            'join_date' => 'required|date',
            'citizenship' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'pancard' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'other_documents.*' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $staff = Staff::create([
            'staffName' => $request->staffName,
            'staffPhone' => $request->staffPhone,
            'staffEmail' => $request->staffEmail,
            'staff_Address' => $request->staff_Address,
            'staffSalary' => $request->staffSalary,
            'department' => $request->department,
            'join_date' => $request->join_date,
        ]);

        $storeDocument = function ($file, $type) use ($staff) {
            $path = $file->store('staff_documents', 'public');
            $staff->documents()->create([
                'type' => $type,
                'filename' => $file->getClientOriginalName(),
                'filepath' => $path,
            ]);
        };

        if ($request->hasFile('citizenship')) {
            $storeDocument($request->file('citizenship'), 'Citizenship');
        }

        if ($request->hasFile('pancard')) {
            $storeDocument($request->file('pancard'), 'PAN Card');
        }

        if ($request->hasFile('other_documents')) {
            foreach ($request->file('other_documents') as $file) {
                $storeDocument($file, 'Other');
            }
        }

        return redirect()->route('staffs.index')->with('success', 'Staff and documents saved successfully.');
    }



    //edit
    public function edit($id)
    {
        $staff = Staff::with('documents')->findOrFail($id);
        return view('backend.staff.edit', compact('staff'));
    }

    //update
    public function update(Request $request, $id)
    {
        $request->validate([
            'staffName' => 'required|string',
            'staffPhone' => 'required|string',
            'staffEmail' => 'required|email',
            'staff_Address' => 'required|string',
            'staffSalary' => 'required|string',
            'department' => 'required|string',
            'join_date' => 'required|date',
            'citizenship' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'pancard' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'other_documents.*' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $staff = Staff::findOrFail($id);

        $staff->update([
            'staffName' => $request->staffName,
            'staffPhone' => $request->staffPhone,
            'staffEmail' => $request->staffEmail,
            'staff_Address' => $request->staff_Address,
            'staffSalary' => $request->staffSalary,
            'department' => $request->department,
            'join_date' => $request->join_date,
        ]);

        $storeDocument = function ($file, $type) use ($staff) {
            $path = $file->store('staff_documents', 'public');
            $staff->documents()->create([
                'type' => $type,
                'filename' => $file->getClientOriginalName(),
                'filepath' => $path,
            ]);
        };

        if ($request->hasFile('citizenship')) {
            $staff->documents()->where('type', 'Citizenship')->delete();
            $storeDocument($request->file('citizenship'), 'Citizenship');
        }

        if ($request->hasFile('pancard')) {
            $staff->documents()->where('type', 'PAN Card')->delete();
            $storeDocument($request->file('pancard'), 'PAN Card');
        }

        if ($request->hasFile('other_documents')) {
            foreach ($request->file('other_documents') as $file) {
                $storeDocument($file, 'Other');
            }
        }

        return redirect()->route('staffs.index')->with('success', 'Staff updated successfully.');
    }




    // Delete staff
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('staffs.index')->with('success', 'Staff deleted!');
    }


    public function exportCsv()
    {
        $staffs = DB::table('staffs')->select(
            'id',
            'staffName',
            'staffPhone',
            'staffEmail',
            'staff_Address',
            'staffSalary',
            'join_date',
            'department'
        )->get();

        $filename = "staffs_" . date('Y-m-d_H_i_s') . ".csv";

        // Open output stream
        $handle = fopen('php://output', 'w');

        // Set headers for download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Add CSV column headers
        fputcsv($handle, [
            'ID',
            'Staff Name',
            'Staff Phone',
            'Staff Email',
            'Staff Address',
            'Staff Salary',
            'Join Date',
            'Department',
        ]);

        // Add data rows
        foreach ($staffs as $staff) {
            fputcsv($handle, [
                $staff->id,
                $staff->staffName,
                $staff->staffPhone,
                $staff->staffEmail,
                $staff->staff_Address,
                $staff->staffSalary,
                $staff->join_date,
                $staff->department,
            ]);
        }

        fclose($handle);
        exit;
    }
}
