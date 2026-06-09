<?php

namespace App\Repositories\Admin;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Prettus\Repository\Eloquent\BaseRepository;


class UserRepository extends BaseRepository
{
    protected $role;

    function model()
    {
        $this->role = new Role();
        return User::class;
    }

    public function index($userTable)
    {
        if (request()['action']) {
            return redirect()->back();
        }

        return view('admin.user.index', ['tableConfig' => $userTable]);
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {
            $user = $this->model->create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'country_code' => $request->country_code,
                'phone' => (string) $request->phone,
                'status' => $request->status,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'postal_code' => $request->postal_code,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'location' => $request->location,
                'skills' => $request->skills,
                'about_me' => $request->about_me,
                'bio' => $request->bio,
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $user->addMediaFromRequest('image')->toMediaCollection('image');
            }

            if ($request->role_id) {
                $role = $this->role->findOrFail($request->role_id);
                $user->assignRole($role);
            }
            
            DB::commit();

            return redirect()->route('admin.user.index')->with('success', __('User Created Successfully'));

        } catch (Exception $e){

            DB::rollback();

            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        try {

            $user = $this->model->findOrFail($id);
            $user->update([
                'country_code' => $request->country_code,
                'email' => $request->email,
                'phone' => (string) $request->phone,
                'status' => $request->status,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'postal_code' => $request->postal_code,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'location' => $request->location,
                'skills' => $request->skills,
                'about_me' => $request->about_me,
                'bio' => $request->bio,
            ]);

            if($request->password)
            {
                $user->password = Hash::make($request->password);
                $user->update();
            }

            $user = $this->model->findOrFail($id);
            if ($user->role === 'super_admin') {
                return redirect()->back()->with('error', __('This user cannot be updated, It is system reserved.'));
            }
            
            if (isset($request->role_id)) {
                $role = $this->role->find($request->role_id);
                $user->syncRoles($role);
            }

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $user->clearMediaCollection('image');
                $user->addMediaFromRequest('image')->toMediaCollection('image');
            }

            DB::commit();
            return redirect()->route('admin.user.index')->with('success', __('User Updated Successfully'));

        } catch (Exception $e) {

            DB::rollback();

            throw $e;
        }
    }

    public function status($id, $status)
    {
        try {

            $user = $this->model->findOrFail($id);
            $user->update(['status' => $status]);

            return json_encode(["resp" => $user]);

        } catch (Exception $e) {

            throw $e;
        }
    }

    public function destroy($id)
    {
        try {

            $user = $this->model->findOrFail($id);
            $user->destroy($id);
            return redirect()->back()->with('success', __('User Deleted Successfully'));

        } catch (Exception $e) {

            throw $e;
        }
    }

    public function updateProfile($request){

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $user->email = $request->input('email');
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->postal_code = $request->input('postal_code');
            $user->country_code = $request->input('country_code');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');
            $user->country_id = $request->input('country_id');
            $user->state_id = $request->input('state_id');
            $user->location = $request->input('location');
            $user->about_me = $request->input('about_me');
            $user->bio = $request->input('bio');

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $user->clearMediaCollection('image');
                $user->addMediaFromRequest('image')->toMediaCollection('image');
            }
            $user->save();

            DB::commit();
            return redirect()->route('admin.user.edit-profile')->with('success', __('Profile Updated Successfully'));

        } catch (Exception $e) {

            DB::rollback();

            throw $e;
        }
    }
    public function removeImage($id)
    {
        $user = User::find($id);
        $user->clearMediaCollection('image');
        return redirect()->back()->with('success', 'Image Removed Successfully');
    }

    public function updatePassword($request)
    {
        try{
            $user = Auth::user();
            $currentPassword = $request->current_password;

            if (Hash::check($currentPassword, $user->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                return redirect()->back()->with('success', 'Password Updated Successfully');
            }
        }catch (Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }
}