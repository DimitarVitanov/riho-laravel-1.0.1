<?php

namespace App\Repositories;

use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class SettingsRepository extends BaseRepository
{
    public function model()
    {
        return Setting::class;
    }

    public function index()
    {
        $settings = $this->model->pluck('values')->first();
        $settingsId = $this->model->pluck('id')->first();

        return view('admin.settings.index', [
            'settings' => $settings,
            'settingsId' => $settingsId,
        ]);
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $settings = $this->model->findOrFail($id);
            $requestData = $request->except(['_token', '_method']);
            if ($request->hasFile('general.light_logo')) {
                $lightLogo = $settings->addMediaFromRequest('general.light_logo')->toMediaCollection('light_logo');
                $lightLogoURL = $lightLogo->getUrl();
                $requestData['general']['light_logo'] = $lightLogoURL;
            } else {
                $requestData['general']['light_logo'] = $settings->values['general']['light_logo'];
            }

            if ($request->hasFile('general.dark_logo')) {
                $darkLogo = $settings->addMediaFromRequest('general.dark_logo')->toMediaCollection('dark_logo');
                $darkLogoURL = $darkLogo->getUrl();
                $requestData['general']['dark_logo'] = $darkLogoURL;
            } else {
                $requestData['general']['dark_logo'] = $settings->values['general']['dark_logo'];
            }

            if ($request->hasFile('general.favicon')) {
                $lightLogo = $settings->addMediaFromRequest('general.favicon')->toMediaCollection('favicon');
                $lightLogoURL = $lightLogo->getUrl();
                $requestData['general']['favicon'] = $lightLogoURL;
            } else {
                $requestData['general']['favicon'] = $settings->values['general']['favicon'];
            }
            
            $this->env($requestData);

            $settings->update([
                'values' => $requestData,
            ]);

            DB::commit();

            return redirect()->route('admin.settings.index')->with('success', 'Settings Updated Successfully');

        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function env($value)
    {
        try {

            $googleRecaptcha = $value['google_reCaptcha'] ?? null;
            if ($googleRecaptcha && is_array($googleRecaptcha)) {
                config([
                    'services.recaptcha.key' => $googleRecaptcha['site_key'],
                    'services.recaptcha.secret' => $googleRecaptcha['secret'],
                ]);
            } else {
                throw new Exception("Google recaptcha are not properly set in the database.");
            }

        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }
}
