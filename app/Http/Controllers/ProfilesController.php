<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DataTables\ProfilesDataTable;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProfilesController extends Controller
{
    public function index(ProfilesDataTable $dataTable)
    {
        return $dataTable->render('profiles.index');
    }

    public function done(Profile $profile): void
    {
        $profile->is_done = true;
        $profile->save();
    }
}
