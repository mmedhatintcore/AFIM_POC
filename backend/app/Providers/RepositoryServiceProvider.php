<?php

namespace App\Providers;

use App\Repositories\AboutRepository;
use App\Repositories\ContactMessageRepository;
use App\Repositories\FaqRepository;
use App\Repositories\FundCategoryRepository;
use App\Repositories\FundRepository;
use App\Repositories\Interfaces\AboutRepositoryInterface;
use App\Repositories\Interfaces\ContactMessageRepositoryInterface;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use App\Repositories\Interfaces\FundCategoryRepositoryInterface;
use App\Repositories\Interfaces\FundRepositoryInterface;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Repositories\Interfaces\SectionRepositoryInterface;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\Interfaces\SurveyRepositoryInterface;
use App\Repositories\NewsRepository;
use App\Repositories\SectionRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\SurveyRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SectionRepositoryInterface::class, SectionRepository::class);
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(FundRepositoryInterface::class, FundRepository::class);
        $this->app->bind(FundCategoryRepositoryInterface::class, FundCategoryRepository::class);
        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);
        $this->app->bind(AboutRepositoryInterface::class, AboutRepository::class);
        $this->app->bind(SurveyRepositoryInterface::class, SurveyRepository::class);
        $this->app->bind(ContactMessageRepositoryInterface::class, ContactMessageRepository::class);
    }
}
