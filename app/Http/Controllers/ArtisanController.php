<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    /**
     * Run migrations via web (Shared Hosting fix).
     * Accessible only via a secret key in .env.
     */
    public function migrate(Request $request)
    {
        $key = $request->query('key');
        $secret = config('app.key'); // Using app key as a basic secret

        if ($key !== $secret) {
            abort(403, 'Unauthorized access.');
        }

        try {
            Artisan::call('migrate', ['--force' => true]);
            return "Migration successful! Output: <br><pre>" . Artisan::output() . "</pre>";
        } catch (\Exception $e) {
            return "Migration failed! Error: " . $e->getMessage();
        }
    }

    public function seed(Request $request)
    {
        $key = $request->query('key');
        $secret = config('app.key');

        if ($key !== $secret) {
            abort(403, 'Unauthorized access.');
        }

        try {
            Artisan::call('db:seed', ['--force' => true]);
            return "Seeding successful! Output: <br><pre>" . Artisan::output() . "</pre>";
        } catch (\Exception $e) {
            return "Seeding failed! Error: " . $e->getMessage();
        }
    }
}
