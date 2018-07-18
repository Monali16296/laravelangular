<?php

namespace App\Http\Controllers;

ini_set('SMTP', '192.168.0.6');

// Please specify an SMTP Number 25 and 8889 are valid SMTP Ports.
ini_set('smtp_port', '25');

// Please specify the return address to use
ini_set('sendmail_from', 'monali.hingu@internal.mail');

use DB;
use Illuminate\Http\Request;

class StudInsertController extends Controller
{
    public function UserList()
    {
        $students_data = DB::table('student')
                ->join('roles', 'roles.id', '=', 'student.role_id')
                ->select('student.*', 'roles.role_name')
                ->where('student.status', '!=', -1)
                ->get();

        if (count($students_data)) {
            return response()->json($students_data);
        } else {
            return response()->json(0);
        }
    }

    public function ActiveAll()
    {
        DB::table('student')
                ->where('student.status', '!=', -1)
                ->where('role_id', '!=', 1)
                ->update(['status' => 1]);

        $students_data['status'] = 1;

        return response()->json($students_data);
    }

    public function InActiveAll()
    {
        DB::table('student')
                ->where('student.status', '!=', -1)
                ->where('role_id', '!=', 1)
                ->update(['status' => 0]);

        $students_data['status'] = 1;

        return response()->json($students_data);
    }

    public function DeleteRecords(Request $request)
    {
        $id = $request->input('items');
        foreach ($id as $a) {
            DB::table('student')
                    ->where('id', $a)
                    ->where('role_id', '!=', 1)
                    ->update(['status' => -1]);
        }

        $students_data['status'] = 1;

        return response()->json($students_data);
    }

    public function ActiveRecords(Request $request)
    {
        $id = $request->input('items');
        foreach ($id as $a) {
            DB::table('student')
                    ->where('id', $a)
                    ->where('status', '!=', -1)
                    ->where('role_id', '!=', 1)
                    ->update(['status' => 1]);
        }

        $students_data['status'] = 1;

        return response()->json($students_data);
    }

    public function InActiveRecords(Request $request)
    {
        $id = $request->input('items');
        foreach ($id as $a) {
            DB::table('student')
                    ->where('id', $a)
                    ->where('status', '!=', -1)
                    ->where('role_id', '!=', 1)
                    ->update(['status' => 0]);
        }

        $students_data['inactive_status'] = 1;

        return response()->json($students_data);
    }

    public function UserSortingList(Request $request)
    {
        $direction = $request->input('direction');
        $sort_by = $request->input('sort_by');
        $students_data = DB::table('student')
                ->join('roles', 'roles.id', '=', 'student.role_id')
                ->select('student.*', 'roles.role_name')
                ->where('student.status', '!=', -1)
                ->orderBy($sort_by, $direction)
                ->get();

        if (count($students_data)) {
            return response()->json($students_data);
        } else {
            return response()->json(0);
        }
    }

    public function UserSearchingList(Request $request)
    {
        $search = $request->input('search');
        if ($search) {
            $students_data = DB::table('student')
                    ->join('roles', 'roles.id', '=', 'student.role_id')
                    ->select('student.*', 'roles.role_name')
                    ->where(function ($query) use ($search) {
                        $query->Where('firstname', 'like', '%'.$search.'%');
                        $query->orWhere('email', 'like', '%'.$search.'%');
                        $query->orWhere('lastname', 'like', '%'.$search.'%');
                    })
                    ->where('student.status', '!=', -1)
                    ->get();

            if (count($students_data)) {
                return response()->json($students_data);
            } else {
                return response()->json(0);
            }
        } else {
            $students_data = DB::table('student')
                    ->join('roles', 'roles.id', '=', 'student.role_id')
                    ->select('student.*', 'roles.role_name')
                    ->where('student.status', '!=', -1)
                    ->get();

            if (count($students_data)) {
                return response()->json($students_data);
            } else {
                return response()->json(0);
            }
        }
    }

    public function UserView(Request $request)
    {
        $id = $request->input('id');

        $students = DB::table('student')
                ->where('id', '=', $id)
                ->get();

        foreach ($students as $d) {
            $students_data['id'] = $d->id;
            $students_data['firstname'] = $d->firstname;
            $students_data['lastname'] = $d->lastname;
            $students_data['email'] = $d->email;
            $students_data['password'] = base64_decode($d->password);
            $students_data['address2'] = $d->address2;
            $students_data['address1'] = $d->address1;
        }

        return response()->json($students_data);
    }

    public function UserDelete(Request $request)
    {
        $id = $request->input('id');

        DB::table('student')
                ->where('id', $id)
                ->update(['status' => -1]);

        $students_data['status'] = 1;

        return response()->json($students_data);
    }

    public function UserEdit(Request $request)
    {
        $id = $request->input('id');

        $students = DB::table('student')
                ->where('id', '=', $id)
                ->get();

        foreach ($students as $d) {
            $students_data['id'] = $d->id;
            $students_data['firstname'] = $d->firstname;
            $students_data['lastname'] = $d->lastname;
            $students_data['email'] = $d->email;
            $students_data['password'] = base64_decode($d->password);
            $students_data['address2'] = $d->address2;
            $students_data['address1'] = $d->address1;
            $students_data['role_id'] = $d->role_id;
            $students_data['status'] = $d->status;
        }

        return response()->json($students_data);
    }

    public function UserEditForm(Request $request)
    {
        $s = $request->get('form');
        $firstname = $s['fname'];

        $name = $s['lname'];
        $email = $s['email'];
        $address1 = $s['add1'];
        $address2 = $s['add2'];
        $password2 = base64_encode($s['password2']);
        $role_id = $s['role'];
        $status = $s['user_status'];

        DB::table('student')
                ->where('email', $email)
                ->update(['role_id' => $role_id, 'firstname' => $firstname, 'lastname' => $name, 'address1' => $address1, 'address2' => $address2, 'password' => $password2, 'status' => $status]);

        $students = DB::table('student')
                ->where('email', $email)
                ->get();

        foreach ($students as $d) {
            $students['role_id'] = $d->role_id;
            $students['id'] = $d->id;
            $students['firstname'] = $d->firstname;
            $students['lastname'] = $d->lastname;
            $students['email'] = $d->email;
            $students['password'] = base64_decode($d->password);
            $students['address2'] = $d->address2;
            $students['address1'] = $d->address1;
        }

        $students['edit_status'] = 1;

        return response()->json($students);
    }

    public function UserAdd(Request $request)
    {
        $address1 = $request->input('address1');
        $address2 = $request->input('address2');
        if ($address1) {
            $add1 = $address1;
        } else {
            $add1 = '';
        }
        if ($address2) {
            $add2 = $address2;
        } else {
            $add2 = '';
        }

        $s = $request->get('form');

        $firstname = $s['fname'];

        $name = $s['lname'];
        $email = $s['email'];

        $password2 = base64_encode($s['password3']);
        $status = $s['user_status'];

        $role = $s['role'];

        $students = DB::table('student')
                ->where('email', $email)
                ->get();

        if (count($students)) {
            $students['email_status'] = 0;
        } else {
            DB::table('student')->insert(
                    ['role_id' => $role, 'firstname' => $firstname, 'lastname' => $name, 'email' => $email, 'address1' => $add1, 'address2' => $add2, 'password' => $password2, 'status' => $status]);
            $students['email_status'] = 1;
        }

        return response()->json($students);
    }

    public function Insert(Request $request)
    {
        $s = $request->get('form');
        $firstname = $s['fname'];

        $name = $s['lname'];
        $email = $s['email'];

        $password2 = base64_encode($s['password3']);

        $students = DB::table('student')
                ->where('email', $email)
                ->get();
        if (count($students)) {
            $students['email_status'] = 0;
        } else {
            DB::table('student')->insert(
                    ['role_id' => 2, 'firstname' => $firstname, 'lastname' => $name, 'email' => $email, 'password' => $password2]);

            $students['email_status'] = 1;
        }

        return response()->json($students);
    }

    public function AdminLogin(Request $request)
    {
        $logemail = $request->input('logemail');

        $logpassword = base64_encode($request->input('logpassword'));

        $students = DB::table('student')
                ->where('email', $logemail)
                ->where('password', $logpassword)
                ->where('status', '!=', 0)
                ->get();

        if (count($students)) {
            foreach ($students as $d) {
                $students_data['id'] = $d->id;
                $students_data['role_id'] = $d->role_id;
                $students_data['firstname'] = $d->firstname;
                $students_data['lastname'] = $d->lastname;
                $students_data['email'] = $d->email;
                $students_data['password'] = base64_decode($d->password);
                $students_data['address2'] = $d->address2;
                $students_data['address1'] = $d->address1;
            }
            $students_data['status'] = 1;

            $students_data['msg'] = 'Logged In';

            return response()->json($students_data);
        } else {
            $students2['msg'] = 'Not registered';
            $students2['status'] = 0;

            return response()->json($students2);
        }
    }

    public function Edit(Request $request)
    {
        $s = $request->get('form');
        $firstname = $s['fname'];

        $name = $s['lname'];
        $email = $s['email'];
        $address1 = $s['add1'];
        $address2 = $s['add2'];
        $password2 = base64_encode($s['password2']);

        DB::table('student')
                ->where('email', $email)
                ->update(['firstname' => $firstname, 'lastname' => $name, 'address1' => $address1, 'address2' => $address2, 'password' => $password2]);

        $students = DB::table('student')
                ->where('email', $email)
                ->get();

        foreach ($students as $d) {
            $students['id'] = $d->id;
            $students['firstname'] = $d->firstname;
            $students['lastname'] = $d->lastname;
            $students['email'] = $d->email;
            $students['password'] = base64_decode($d->password);
            $students['address2'] = $d->address2;
            $students['address1'] = $d->address1;
        }

        $students['status'] = 1;

        return response()->json($students);
    }

    public function ChangePassword(Request $request)
    {
        $id = $request->input('id');

        $s = $request->get('form');
        $old_password = base64_encode($s['password']);
        $new_password = base64_encode($s['password2']);

        $students = DB::table('student')
                ->where('password', $old_password)
                ->get();
        if ($students) {
            DB::table('student')
                    ->where('id', $id)
                    ->update(['password' => $new_password]);
            $students['change_status'] = 1;
        } else {
            $students['change_status'] = 0;
        }

        return response()->json($students);
    }

    public function ForgotPassword(Request $request)
    {
        $email = $request->input('email');

        $students = DB::table('student')
                ->where('email', $email)
                ->where('status', 1)
                ->get();

        if ($students) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$&';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 4; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            $d = '0123456789';
            $dLength = strlen($d);
            $randomString .= $d[rand(0, $dLength - 1)];

            $u = 'abcdefghijklmnopqrstuvwxyz';
            $uLength = strlen($u);
            $randomString .= $u[rand(0, $uLength - 1)];

            $l = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $lLength = strlen($l);
            $randomString .= $l[rand(0, $lLength - 1)];

            $s = '@#$&';
            $sLength = strlen($s);
            $randomString .= $s[rand(0, $sLength - 1)];

            DB::table('student')
                    ->where('email', $email)
                    ->update(['password' => base64_encode($randomString)]);

            $students['forgot_status'] = 1;
            $to = $email;
            $subject = 'Forgot Password';
            $txt = 'Your password is '.$randomString;

            mail($email, $subject, $txt);
        } else {
            $students['forgot_status'] = 0;
        }

        return response()->json($students);
    }
}
