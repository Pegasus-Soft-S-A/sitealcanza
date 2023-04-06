<?php

use Theme\Martfury\Http\Controllers\MartfuryController;

// Custom routes
Route::group(['controller' => MartfuryController::class, 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::group(['prefix' => 'ajax', 'as' => 'public.ajax.'], function () {
            Route::get('products', 'ajaxGetProducts')
                ->name('products');

            Route::get('featured-product-categories', 'getFeaturedProductCategories')
                ->name('featured-product-categories');

            Route::get('trending-products', 'ajaxGetTrendingProducts')
                ->name('trending-products');

            Route::get('featured-brands', 'ajaxGetFeaturedBrands')
                ->name('featured-brands');

            Route::get('featured-products', 'ajaxGetFeaturedProducts')
                ->name('featured-products');

            Route::get('top-rated-products', 'ajaxGetTopRatedProducts')
                ->name('top-rated-products');

            Route::get('on-sale-products', 'ajaxGetOnSaleProducts')
                ->name('on-sale-products');

            Route::get('cart', 'ajaxCart')
                ->name('cart');

            Route::get('quick-view/{id}', 'getQuickView')
                ->name('quick-view');

            Route::get('featured-posts', 'ajaxGetFeaturedPosts')
                ->name('featured-posts');

            Route::get('related-products/{id}', 'ajaxGetRelatedProducts')
                ->name('related-products');

            Route::get('product-reviews/{id}', 'ajaxGetProductReviews')
                ->name('product-reviews');

            Route::get('search-products', 'ajaxSearchProducts')
                ->name('search-products');

            Route::post('send-download-app-links', 'ajaxSendDownloadAppLinks')
                ->name('send-download-app-links');

            Route::get('product-categories/products', 'ajaxGetProductsByCategoryId')
                ->name('product-category-products');

            Route::get('get-product-categories', 'ajaxGetProductCategories')
                ->name('get-product-categories');

            Route::get('get-flash-sale/{id}', 'ajaxGetFlashSale')
                ->name('get-flash-sale');
        });
    });
});

Theme::routes();
