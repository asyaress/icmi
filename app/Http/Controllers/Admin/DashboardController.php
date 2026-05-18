<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Permission;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Models\Video;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'userCount' => User::count(),
            'roleCount' => Role::count(),
            'permissionCount' => Permission::count(),
            'activeUsersCount' => User::where('is_active', true)->count(),
            'newsCount' => Post::query()->type(Post::TYPE_NEWS)->count(),
            'opinionCount' => Post::query()->type(Post::TYPE_OPINION)->count(),
            'mediaInfoCount' => Post::query()->type(Post::TYPE_MEDIA_INFO)->count(),
            'galleryCount' => Gallery::count(),
            'videoCount' => Video::count(),
        ]);
    }
}
