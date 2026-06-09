<?php

namespace App\Repositories\Admin;

use App\Models\LandingPage;
use Exception;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class LandingPageRepository extends BaseRepository
{
    public function model()
    {
        return LandingPage::class;
    }

    public function index()
    {
        try {

            $content = LandingPage::pluck('content')?->first();
            return view('admin.landing-page.index', ['id' => $this->model->pluck('id')->first(), 'content' => $content]);
        } catch (Exception $e) {

            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        try {
            $landingPage = $this->model->findOrFail($id);
            $requestData = $request->except(['_token', '_method']);
            $fields = [
                'header' => ['logo'],
                'home' => ['main_img', 'sidebar_cuts_image', 'left_bottom_image', 'left_top_image', 'right_top_image', 'right_bottom_image'],
                'feature' => ['premium_support' => ['main_image']],
                'footer' => ['logo'],
            ];

            foreach ($fields as $fKey => $field) {
                foreach ($field as $iKey => $images) {
                    if (is_array($images)) {

                        foreach ($images as $key => $img) {
                            if (is_array($img)) {
                                foreach ($img as $i) {
                                    if ($request->hasFile("{$fKey}.{$iKey}.{$key}.{$i}")) {
                                        $image = $landingPage->addMediaFromRequest("$fKey.$iKey.$key.$i")->toMediaCollection($key);
                                        $imageURL = $image->getUrl();
                                        $requestData[$fKey][$iKey][$key][$i] = $imageURL;
                                    } else {
                                        $requestData[$fKey][$iKey][$key][$i] = $landingPage->content[$fKey][$iKey][$key][$i];
                                    }
                                }
                            } else {
                                if ($request->hasFile("{$fKey}.{$iKey}.{$img}")) {
                                    $image = $landingPage->addMediaFromRequest("$fKey.$iKey.$img")->toMediaCollection($iKey);
                                    $imageURL = $image->getUrl();
                                    $requestData[$fKey][$iKey][$img] = $imageURL;
                                } else {
                                    $requestData[$fKey][$iKey][$img] = $landingPage->content[$fKey][$iKey][$img];
                                }
                            }
                        }
                    } else {
                        if ($request->hasFile("{$fKey}.{$images}")) {
                            $image = $landingPage->addMediaFromRequest("$fKey.$images")->toMediaCollection($fKey);
                            $imageURL = $image->getUrl();
                            $requestData[$fKey][$images] = $imageURL;
                        } else {
                            $requestData[$fKey][$images] = $landingPage->content[$fKey][$images];
                        }
                    }
                }
            }

            //update cruds posters images
            foreach ($request['home']['laravel_crud']['crud_banners'] ?? [] as $index => $poster) {
                if ($request->hasFile("home.laravel_crud.crud_banners.{$index}.image")) {
                    $image = $landingPage->addMediaFromRequest("home.laravel_crud.crud_banners.{$index}.image")->toMediaCollection('laravel_crud');
                    $imageURL = $image->getUrl();
                    $requestData['home']['laravel_crud']['crud_banners'][$index]['image'] = $imageURL;
                } else {
                    $requestData['home']['laravel_crud']['crud_banners'][$index]['image'] = $landingPage->content['home']['laravel_crud']['crud_banners'][$index]['image'] ?? null;
                }
            }

            //update dashboard posters images
            foreach ($request['page']['dashboard'] ?? [] as $index => $poster) {
                if ($request->hasFile("page.dashboard.{$index}.image")) {
                    $image = $landingPage->addMediaFromRequest("page.dashboard.{$index}.image")->toMediaCollection('dashboard');
                    $imageURL = $image->getUrl();
                    $requestData['page']['dashboard'][$index]['image'] = $imageURL;
                } else {
                    $requestData['page']['dashboard'][$index]['image'] = $landingPage->content['page']['dashboard'][$index]['image'] ?? null;
                }
            }

            //update frameworks posters
            foreach ($request['page']['frameworks']['poster'] ?? [] as $index => $poster) {
                if ($request->hasFile("page.frameworks.poster.{$index}.image")) {
                    $image = $landingPage->addMediaFromRequest("page.frameworks.poster.{$index}.image")->toMediaCollection('page_layout_poster');
                    $imageURL = $image->getUrl();
                    $requestData['page']['frameworks']['poster'][$index]['image'] = $imageURL;
                } else {
                    $requestData['page']['frameworks']['poster'][$index]['image'] = $landingPage->content['page']['frameworks']['poster'][$index]['image'] ?? null;
                }
            }

            //update application posters
            foreach ($request['page']['applications']['poster'] ?? [] as $index => $poster) {
                if ($request->hasFile("page.applications.poster.{$index}.image")) {
                    $image = $landingPage->addMediaFromRequest("page.applications.poster.{$index}.image")->toMediaCollection('applications_poster');
                    $imageURL = $image->getUrl();
                    $requestData['page']['applications']['poster'][$index]['image'] = $imageURL;
                } else {
                    $requestData['page']['applications']['poster'][$index]['image'] = $landingPage->content['page']['applications']['poster'][$index]['image'] ?? null;
                }
            }

            //update feature posters
            foreach ($request['feature']['laravel_feature']['poster'] ?? [] as $index => $poster) {
                if ($request->hasFile("feature.laravel_feature.poster.{$index}.svg_icon")) {
                    $image = $landingPage->addMediaFromRequest("feature.laravel_feature.poster.{$index}.svg_icon")->toMediaCollection('feature_svg_icon');
                    $imageURL = $image->getUrl();
                    $requestData['feature']['laravel_feature']['poster'][$index]['svg_icon'] = $imageURL;
                } else {
                    $requestData['feature']['laravel_feature']['poster'][$index]['svg_icon'] = $landingPage->content['feature']['laravel_feature']['poster'][$index]['svg_icon'] ?? null;
                }
            }

            $landingPage->update([
                'content' => $requestData,
            ]);

            DB::commit();
            return redirect()->route('admin.landing.index')->with('success', 'Landing Page Updated Successfully');

        } catch (Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }
}
