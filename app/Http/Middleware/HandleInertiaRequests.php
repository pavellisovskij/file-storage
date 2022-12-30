<?php

namespace App\Http\Middleware;

use App\Models\File;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $data = [
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => function () {
                return (new Ziggy)->toArray();
            },
            'appName' => config('app.name'),
        ];

        if (explode('.', $request->route()->getName())[0] != 'admin' && auth()->user()) {
            $data['currentRouteName'] = $request->route()->getName();

            $sum = File::query()
                ->where('user_id', auth()->id())
                ->sum('size');

            $data['maxStorageSpace'] = round($request->user()->storage_size, 2);
            $data['filledStorageSpace'] = round($sum / 1024 / 1024, 2);

            if ($request->route()->named('storage.folder'))
                $data['currentFolder'] = $request->route()->parameter('folder')->encoded_name;
        }

        return array_merge(parent::share($request), $data);
    }
}
