<?php

namespace Application\Presentation\Controllers\Admin;

use Application\Business\Interfaces\ServiceInterfaces\CategoryServiceInterface;
use Application\Business\Interfaces\ServiceInterfaces\ProductServiceInterface;
use Application\Business\Interfaces\ServiceInterfaces\StatisticsServiceInterface;
use Application\Integration\Utility\PathHelper;
use Infrastructure\HTTP\HttpRequest;
use Infrastructure\HTTP\Response\HtmlResponse;
use Infrastructure\HTTP\Response\JsonResponse;
use Infrastructure\Utility\SessionManager;

class DashboardController extends AdminController
{
    /**
     * DashboardController constructor.
     *
     * @param CategoryServiceInterface $categoryService
     * @param ProductServiceInterface $productService
     * @param StatisticsServiceInterface $statisticsService
     */
    public function __construct(
        protected CategoryServiceInterface   $categoryService,
        protected ProductServiceInterface    $productService,
        protected StatisticsServiceInterface $statisticsService
    )
    {
    }

    /**
     * Display the dashboard page or redirect to the login page if the user is not logged in.
     *
     * @param HttpRequest $request The HTTP request object.
     * @return HtmlResponse The HTML response with the appropriate view.
     */
    public function index(HttpRequest $request): HtmlResponse
    {
        if (SessionManager::getInstance()->get('user_id')) {
            return HtmlResponse::fromView(PathHelper::view('dashboard.php'));
        }

        return HtmlResponse::fromView(PathHelper::view('login.php'));
    }

    /**
     * Get the statistics data for the dashboard.
     *
     * @param HttpRequest $request The HTTP request object.
     * @return JsonResponse The JSON response with the statistics data.
     */
    public function getStatistics(HttpRequest $request): JsonResponse
    {
        $statistics = [
            'productCount' => $this->productService->getProductsCount(),
            'categoryCount' => $this->categoryService->getCategoriesCount(),
            'homeViewCount' => $this->statisticsService->getHomeViewCount(),
            'mostViewedProduct' => $this->productService->getMostViewedProduct(),
        ];

        return new JsonResponse(200, [], $statistics);
    }
}
