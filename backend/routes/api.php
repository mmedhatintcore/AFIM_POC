<?php

use App\Http\Controllers\Api\V1\CommitteeController;
use App\Http\Controllers\Api\V1\ContactMessageController;
use App\Http\Controllers\Api\V1\FaqController;
use App\Http\Controllers\Api\V1\FundCategoryController;
use App\Http\Controllers\Api\V1\FundController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\SectionController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\SurveyController;
use App\Http\Controllers\Api\V1\TeamMemberController;
use App\Http\Controllers\Api\V1\TimelineMilestoneController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('set.locale')->group(function () {
    // CMS content
    Route::get('/sections', [SectionController::class, 'index']);
    Route::get('/sections/{key}', [SectionController::class, 'show']);

    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/{slug}', [ServiceController::class, 'show']);

    Route::get('/funds', [FundController::class, 'index']);
    Route::get('/funds/{slug}', [FundController::class, 'show']);
    Route::get('/fund-categories', [FundCategoryController::class, 'index']);

    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/news/{slug}', [NewsController::class, 'show']);

    Route::get('/faqs', [FaqController::class, 'index']);

    Route::get('/timeline-milestones', [TimelineMilestoneController::class, 'index']);
    Route::get('/team-members', [TeamMemberController::class, 'index']);
    Route::get('/committees', [CommitteeController::class, 'index']);

    // Investment survey
    Route::get('/survey/questions', [SurveyController::class, 'questions']);
    Route::post('/survey/submissions', [SurveyController::class, 'submit'])->middleware('throttle:survey');

    // Contact
    Route::post('/contact-messages', [ContactMessageController::class, 'store'])->middleware('throttle:contact');
});
