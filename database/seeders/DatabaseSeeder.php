<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;

class DatabaseSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->activateAllPlugins();

        $this->call(LanguageSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductCollectionSeeder::class);
        $this->call(ProductLabelSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductAttributeSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(TaxSeeder::class);
        $this->call(ProductTagSeeder::class);
        $this->call(FlashSaleSeeder::class);
        $this->call(ShippingSeeder::class);
        $this->call(ContactSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BlogSeeder::class);
        $this->call(SimpleSliderSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(AdsSeeder::class);
        $this->call(FaqSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(StoreLocatorSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(ThemeOptionSeeder::class);
        $this->call(WidgetSeeder::class);
        $this->call(ProductOptionSeeder::class);

        if (is_plugin_active('marketplace')) {
            $this->call(MarketplaceSeeder::class);
        }

        $this->call(OrderEcommerceSeeder::class);

        $this->finished();
    }
}
