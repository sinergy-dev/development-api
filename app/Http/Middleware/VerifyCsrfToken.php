<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'job/postJobApply',
        'job/postJobStart',
        'job/postJobUpdate',
        'job/postJobFinish',
        'job/createJob/postPublishJobs',
        'job/createJob/postQRRecive',
        'job/createJob/postPDFRecive',
        'job/createJob/postLetter',
        'job/postApplyerUpdate',
        'job/postReviewedByModerator',
        'job/postFinishedByModerator',
        'job/postPayedByModerator',
        'job/postUpdatePay',
        'job/postPayedByModeratorInvoice',
        'job/postPayedByModeratorFirst',
        'job/postPayedByModeratorSecond',
        'engineer/postNewEngineer',
        'client/postNewClient',
        'engineer/updateEngineerData',
        'dashboard/getJobListAndSumary/search',
     


        // 'dashboard/getDashboard',

    ];
}
