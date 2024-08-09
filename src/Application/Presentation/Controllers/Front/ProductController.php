<?php

namespace Application\Presentation\Controllers\Front;

use Infrastructure\HTTP\Response\HtmlResponse;

class ProductController extends FrontController
{
    public function list(): HtmlResponse
    {
        // Code for rendering category products
    }

    public function index(int $id): HtmlResponse
    {
        // Code for rendering product details
    }
}
