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
        'job/postApplyerUpdate',
        'job/postReviewedByModerator',
        'job/postFinishedByModerator',
        'job/postPayedByModerator',
        'job/postUpdatePay',
        'job/postPayedByModeratorInvoice',
        'job/postPayedByModeratorFirst',
        'job/postPayedByModeratorSecond',
        


        // 'dashboard/getDashboard',

    ];
}
