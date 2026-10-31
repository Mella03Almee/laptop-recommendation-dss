<?php

namespace App\Providers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use App\Models\SubKriteria1;
use App\Models\SubKriteria2;
use App\Models\SubKriteria3;
use App\Models\SubKriteria4;
use App\Models\SubKriteria5;
/*use App\Models\SubKriteria6;*/
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function (User $user) {
            return $user->is_admin;
        });

        Gate::define('kriteria', function (User $user, Kriteria $data_kriterium) {
            return $user->id === $data_kriterium->user_id;
        });

        Gate::define('subkriteria', function (User $user, SubKriteria $data_sub_kriterium) {
            return $user->id === $data_sub_kriterium->user_id;
        });

        Gate::define('subkriteria1', function (User $user, SubKriteria1 $data_sub_kriteria1) {
            return $user->id === $data_sub_kriteria1->user_id;
        });

        Gate::define('subkriteria2', function (User $user, SubKriteria2 $data_sub_kriteria2) {
            return $user->id === $data_sub_kriteria2->user_id;
        });

        Gate::define('subkriteria3', function (User $user, SubKriteria3 $data_sub_kriteria3) {
            return $user->id === $data_sub_kriteria3->user_id;
        });

        Gate::define('subkriteria4', function (User $user, SubKriteria4 $data_sub_kriteria4) {
            return $user->id === $data_sub_kriteria4->user_id;
        });

        Gate::define('subkriteria5', function (User $user, SubKriteria5 $data_sub_kriteria5) {
            return $user->id === $data_sub_kriteria5->user_id;
        });

        /* Gate::define('subkriteria6', function (User $user, SubKriteria6 $data_sub_kriteria6) {
            return $user->id === $data_sub_kriteria6->user_id;
        }); */

        Gate::define('alternatif', function (User $user, Alternatif $data_alternatif) {
            return $user->id === $data_alternatif->user_id;
        });

        Gate::define('penilaian', function (User $user, Penilaian $data_penilaian) {
            return $user->id === $data_penilaian->user_id;
        });
    }
}
