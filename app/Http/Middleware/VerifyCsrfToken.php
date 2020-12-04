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
        'job/updateJob/postPublishJobsEdit',
        'job/createJob/postQRRecive',
        'job/createJob/postPDFRecive',
        'job/createJob/postLetter',
        'job/postChatModerator',
        'job/postApplyerUpdate',
        'job/postReviewedByModerator',
        'job/postFinishedByModerator',
        'job/postPayedByModerator',
        'job/postUpdatePay',
        'job/postPayedByModeratorInvoice',
        'job/postPayedByModeratorFirst',
        'job/postPayedByModeratorSecond',
        'job/postStatusRequestItem',
        'job/getJobBast',
        'engineer/postNewEngineer',
        'engineer/updateEngineerData',
        'client/postNewClient',
        'client/updateClient',
        // 'dashboard/getJobListAndSumary/search',
        'dashboard/getJobListAndSumary/FilterStatus',
        'join/postBasicJoin',
        'join/postAdvancedJoin',
        'join/postSubmitPartner',
        'join/postScheduleInterview',
        'join/postStartInterview',
        'join/postResultInterview',
        'join/postAgreementInterview',
        'join/postPartnerAgreement',
        'partner/getNewPartnerIdentifier',
        'job/postStatusRequestSupport',
        'testing',
        'setting/category/postCategory',
        'setting/category/postUpdateCategory',
        'setting/category/postCategoryMain'
        
     


        // 'dashboard/getDashboard',

    ];
}
