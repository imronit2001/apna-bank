<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin;
use App\Models\Branch;
use App\Models\Staff;
use App\Models\AddManagerForm;
use App\Models\AddStaffForm;
use App\Models\change_branch;
use App\Models\CustomerData;
use App\Models\FixedDeposit;
use App\Models\Helpline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    function check(Request $request)
    {
        //Validate Inputs
        // dd($request);
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|min:5|max:30'
        ], [
            'email.exists' => 'This email is not exists in admins table'
        ]);

        $creds = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($creds)) {
            $customers = CustomerData::all();
            $customer = $customers->count();
            $staffs = Staff::all();
            $staff = $staffs->count();
            $managers = Admin::all();
            $manager = $managers->count();
            $branches = Admin::all();
            $branch = $branches->count();
            $helplines = Helpline::all();
            $helpline = $helplines->count();
            $fds = FixedDeposit::all();
            $fd = $fds->count();
            $changeBranch = change_branch::where("status", 0)->get();
            $changeBranchList = $changeBranch->count();


            $data = ['customer' => $customer, 'staff' => $staff, 'manager' => $manager, 'changeBranchList' => $changeBranchList, 'branch' => $branch, 'fd' => $fd, 'helpline' => $helpline];

            return redirect()->route('admin-dashboard', $data);
        } else {
            return redirect()->route('admin-login')->with('fail', 'Incorrect credentials');
        }
    }

    function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }



    public function dashboard()
    {
        $customers = CustomerData::all();
        $customer = $customers->count();
        $staffs = Staff::all();
        $staff = $staffs->count();
        $managers = Admin::all();
        $manager = $managers->count();
        $branches = Admin::all();
        $branch = $branches->count();
        $helplines = Helpline::all();
        $helpline = $helplines->count();
        $fds = FixedDeposit::all();
        $fd = $fds->count();
        $changeBranch = change_branch::where("status", 0)->get();
        $changeBranchList = $changeBranch->count();


        $data = ['customer'=>$customer,'staff' => $staff, 'manager' => $manager, 'changeBranchList' => $changeBranchList, 'branch' => $branch, 'fd' => $fd, 'helpline' => $helpline];


        return view('admin.dashboard', $data);
    }
    public function bankBalance()
    {
        return view('admin.bank-balance');
    }
    public function getBranch()
    {
        $branch = Branch::all();
        return $branch;
    }
    public function addStaffIndex()
    {
        $branch = Branch::all();
        return view('admin.add-staff', ['branch' => $branch]);
    }
    public function addStaffCreate(Request $request)
    {
        $staff = new Staff();
        $staff->fullName = $request->fullName;
        $staff->email = $request->email;
        $staff->mobile = $request->mobile;
        $staff->dob = $request->dob;
        $branch = Branch::find($request->branch);
        $staff->branch = $branch->branch_id_prefix . $branch->branch_code . " " . $branch->branch_name;
        $staff->address = $request->address;
        $staff->pin = $request->pin;

        $laststaff = Staff::latest()->first();
        if ($laststaff == "") {
            $staff->userid = "staff" . "0001";
        } else {
            $staffId = explode("staff", $laststaff->userid);
            $staff->userid = "staff" . sprintf("%04d", $staffId[1] + 1);
        }
        $staff->password = Hash::make(12345);
        $staff->pin = "1234";
        $staff->save();
        return redirect((route('admin-add-staff')));
    }
    public function addManagerIndex()
    {
        $branch = Branch::all();
        return view('admin.add-manager', ['branch' => $branch]);
    }
    public function addManagerCreate(Request $request)
    {
        $admin = new Admin();
        $admin->fullName = $request->fullName;
        $admin->email = $request->email;
        $admin->mobile = $request->mobile;
        $admin->dob = $request->dob;
        $branch = Branch::find($request->branch);
        $admin->branch = $branch->branch_id_prefix . $branch->branch_code . " " . $branch->branch_name;
        $admin->address = $request->address;
        $admin->pin = $request->pin;

        $lastadmin = Admin::latest()->first();
        if ($lastadmin == "") {
            $admin->userid = "admin" . "0001";
        } else {
            $adminId = explode("admin", $lastadmin->userid);
            $admin->userid = "admin" . sprintf("%04d", $adminId[1] + 1);
        }
        // $admin->password = "12345";
        $admin->password = Hash::make(12345);

        $admin->pin = "1234";
        $admin->save();
        return redirect((route('admin-add-manager')));
    }
    public function manageStaff()
    {
        $staff = Staff::all();
        return view('admin.manage-staff', ['staff' => $staff]);
    }
    public function staffDetails($id)
    {
        $staff = AddStaffForm::find($id);
        return view('admin.staff-details', ['staff' => $staff]);
    }
    public function removeStaff($id, $status)
    {
        $staff = Staff::find($id);
        $staff->status = $status;
        $staff->save();
        return redirect((route('admin-manage-staff')));
    }
    public function managers()
    {
        $manager = Admin::all();
        return view('admin.manager', ['manager' => $manager]);
    }
    public function manageCustomer()
    {
        return view('admin.manage-customer');
    }
    public function managerDetails($id)
    {
        $manager = AddManagerForm::find($id);
        return view('admin.manager-details', ['manager' => $manager]);
    }
    public function removeManager($id, $status)
    {
        $admin = Admin::find($id);
        $admin->status = $status;
        $admin->save();
        return redirect((route('admin-manage-admin')));
    }
    public function customerTransactionDetail()
    {
        return view('admin.customer-transaction-detail');
    }
    public function branchChange()
    {
        $applications = change_branch::where("status", 0)->get();
        return view('admin.branch-change', ['applications' => $applications]);
    }
    public function branchChangeView($id)
    {
        $applications = change_branch::find($id);
        return view('admin.branch-change-view', ['applications' => $applications]);
    }
    public function branchChangeApprove($id)
    {
        $application = change_branch::find($id);
        $application->status = "1";
        $application->save();
        $applications = change_branch::where("status", 0)->get();
        return redirect((route('admin-branch-change', ['applications' => $applications])));
    }
    public function branchChangeDecline($id)
    {
        $application = change_branch::find($id);
        $application->status = "2";
        $application->save();
        $applications = change_branch::where("status", 0)->get();
        return redirect((route('admin-branch-change', ['applications' => $applications])));
    }
    public function fixedDeposit()
    {
        return view('admin.fixed-deposit');
    }
    public function termsCondition()
    {
        return view('admin.termsCondition');
    }
}
