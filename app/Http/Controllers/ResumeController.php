<?php

namespace App\Http\Controllers;

use App\Models\SiteCopy;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResumeController extends Controller
{
    public function __invoke(): BinaryFileResponse
    {
        abort_unless(Schema::hasTable('site_copies'), 404);

        $copy = SiteCopy::current();
        $path = $copy->resumeDiskPath();

        abort_if($path === null, 404);

        return response()->download($path, $copy->resumeDownloadName(), [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }
}
