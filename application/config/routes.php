<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$routes = $this->config->item('routes');

$route['default_controller'] = 'home_controller';
$route['404_override'] = 'home_controller/error_404';
$route['translate_uri_dashes'] = FALSE;
$route['error-404'] = 'home_controller/error_404';
$route['notification'] = 'notification';

$general_settings = $this->config->item('general_settings');
$languages = $this->config->item('languages');
foreach ($languages as $language) {
    if ($language->status == 1) {
        $key = "";
        if ($general_settings->site_lang != $language->id) {
            $key = $language->short_form . '/';
            $route[$language->short_form] = 'home_controller/index';
            $route[$key . '/error-404'] = 'home_controller/error_404';
        }

        //auth
        $route[$key . $routes->register_buyer]['GET'] = 'auth_controller/register_buyer';
        $route[$key . $routes->register_seller]['GET'] = 'auth_controller/register_seller';
        $route[$key . $routes->forgot_password]['GET'] = 'auth_controller/forgot_password';
        $route[$key . $routes->reset_password]['GET'] = 'auth_controller/reset_password';
        //profile
        $route[$key . $routes->profile . '/(:any)']['GET'] = 'profile_controller/profile/$1';
        $route[$key . $routes->wishlist . '/(:any)']['GET'] = 'profile_controller/wishlist/$1';
        $route[$key . $routes->wishlist]['GET'] = 'home_controller/guest_wishlist/$1';
        $route[$key . $routes->followers . '/(:any)']['GET'] = 'profile_controller/followers/$1';
        $route[$key . $routes->following . '/(:any)']['GET'] = 'profile_controller/following/$1';
        $route[$key . $routes->reviews . '/(:any)']['GET'] = 'profile_controller/reviews/$1';
        $route[$key . $routes->log_activity]['GET'] = 'profile_controller/log_activity';
        /*settings*/
        $route[$key . $routes->settings]['GET'] = 'profile_controller/update_profile';
        $route[$key . $routes->settings . '/' . $routes->update_profile]['GET'] = 'profile_controller/update_profile';
        $route[$key . $routes->settings . '/' . $routes->shop_settings]['GET'] = 'profile_controller/shop_settings';
        $route[$key . $routes->settings . '/' . $routes->personal_information]['GET'] = 'profile_controller/personal_information';
        $route[$key . $routes->settings . '/' . $routes->social_media]['GET'] = 'profile_controller/social_media';
        $route[$key . $routes->settings . '/' . $routes->change_password]['GET'] = 'profile_controller/change_password';
        $route[$key . $routes->settings . '/' . $routes->shipping_address]['GET'] = 'profile_controller/shipping_address';
        $route[$key . $routes->members]['GET'] = 'home_controller/members';
        /*product*/
        $route[$key . $routes->start_selling]['GET'] = 'product_controller/start_selling';
        $route[$key . $routes->sell_now]['GET'] = 'product_controller/add_product';
        $route[$key . $routes->sell_now . '/(:num)']['GET'] = 'product_controller/edit_draft/$1';
        $route[$key . $routes->sell_now . '/' . $routes->product_details . '/(:num)']['GET'] = 'product_controller/edit_product_details/$1';
        $route[$key . $routes->sell_now . '/' . $routes->edit_product . '/(:num)']['GET'] = 'product_controller/edit_product/$1';
        $route[$key . $routes->search]['GET'] = 'home_controller/search';
        $route[$key . $routes->products]['GET'] = 'home_controller/products';
        $route[$key . $routes->drafts]['GET'] = 'profile_controller/drafts';
        $route[$key . $routes->downloads]['GET'] = 'profile_controller/downloads';
        $route[$key . $routes->pending_products]['GET'] = 'profile_controller/pending_products';
        $route[$key . $routes->hidden_products]['GET'] = 'profile_controller/hidden_products';
        /*promoted product*/
        $route[$key . $routes->promote_product . '/' . $routes->pricing . '/(:num)']['GET'] = 'promote_controller/pricing/$1';
        /*blog*/
        $route[$key . $routes->guide . '/(:any)']['GET'] = 'home_controller/blog_category/$1';
        $route[$key . $routes->guide . '/' . $routes->tag . '/(:any)']['GET'] = 'home_controller/tag/$1';
        $route[$key . $routes->guide . '/(:any)/(:any)']['GET'] = 'home_controller/post/$1/$2';
        /*messages*/
        $route[$key . $routes->messages]['GET'] = 'message_controller/messages';
        $route[$key . $routes->messages . '/' . $routes->conversation . '/(:num)']['GET'] = 'message_controller/conversation/$1';
        /*rss feeds*/
        $route[$key . $routes->rss_feeds]['GET'] = 'rss_controller/rss_feeds';
        $route[$key . 'rss/' . $routes->latest_products]['GET'] = 'rss_controller/latest_products';
        $route[$key . 'rss/' . $routes->featured_products]['GET'] = 'rss_controller/featured_products';
        $route[$key . 'rss/' . $routes->category . '/(:any)']['GET'] = 'rss_controller/rss_by_category/$1';
        $route[$key . 'rss/' . $routes->seller . '/(:any)']['GET'] = 'rss_controller/rss_by_seller/$1';
        /*cart*/
        $route[$key . $routes->cart]['GET'] = 'cart_controller/cart';
        $route[$key . $routes->cart . '/' . $routes->shipping]['GET'] = 'cart_controller/shipping';
        $route[$key . $routes->cart . '/' . $routes->payment_method]['GET'] = 'cart_controller/payment_method';
        $route[$key . $routes->cart . '/' . $routes->payment]['GET'] = 'cart_controller/payment';
        $route[$key . $routes->cart . '/' . $routes->token_midtrans]['GET'] = 'cart_controller/token_midtrans';
        $route[$key . $routes->cart . '/' . $routes->token_midtrans]['POST'] = 'cart_controller/token_midtrans'; 
        $route[$key . $routes->cart . '/' . $routes->negotiation]['GET'] = 'cart_controller/negotiation';
        $route[$key . $routes->cart . '/' . $routes->make_an_offer]['GET'] = 'cart_controller/make_an_offer';
        /*orders*/
        $route[$key . $routes->orders]['GET'] = 'order_controller/orders';
        $route[$key . $routes->orders . '/' . $routes->completed_orders]['GET'] = 'order_controller/completed_orders';
        $route[$key . $routes->order_details . '/(:num)']['GET'] = 'order_controller/order/$1';
        $route[$key . $routes->order_completed . '/(:num)']['GET'] = 'cart_controller/order_completed/$1';
        $route[$key . $routes->promote_payment_completed]['GET'] = 'cart_controller/promote_payment_completed';
        $route[$key . 'invoice/(:num)']['GET'] = 'order_controller/invoice/$1';
        /*sales*/
        $route[$key . $routes->sales]['GET'] = 'order_controller/sales';
        $route[$key . $routes->sales . '/' . $routes->completed_sales]['GET'] = 'order_controller/completed_sales';
        // $route[$key . $routes->sales . '/' . $routes->need_confirmation]['GET'] = 'order_controller/need_confirmation';
        $route[$key . $routes->sale . '/(:num)']['GET'] = 'order_controller/sale/$1';
        /*earnings*/
        $route[$key . $routes->earnings]['GET'] = 'earnings_controller/earnings';
        $route[$key . $routes->set_payout_account]['GET'] = 'earnings_controller/set_payout_account';
        $route[$key . $routes->payouts]['GET'] = 'earnings_controller/payouts';
        /*bidding*/
        $route[$key . $routes->quote_requests]['GET'] = 'bidding_controller/quote_requests';
        $route[$key . $routes->sent_quote_requests]['GET'] = 'bidding_controller/sent_quote_requests';
        /*Multi Login*/
        $route[$key . 'login/buyer']['GET'] = 'buyer_controller/index';
        $route[$key . 'login/supervisor']['GET'] = 'supervisor_controller/index';
        $route[$key . 'login/seller']['GET'] = 'seller_controller/index';
        $route[$key . 'login/partners']['GET'] = 'partners_controller/index';
        /*any*/
        if ($general_settings->site_lang != $language->id) {
            $route[$key . '(:any)/(:any)']['GET'] = 'home_controller/subcategory/$1/$2';
            $route[$key . '(:any)']['GET'] = 'home_controller/any/$1';
        }
    }
}

// echo "<pre>";
// var_dump($route);
// echo "</pre>";
// exit();

//static routes
include_once "routes_static.php";

/*
 *
 * ADMIN ROUTES
 *
 */
$route[$routes->admin] = 'admin_controller/index';
//login
$route[$routes->admin . '/login'] = 'common_controller/admin_login';
$route[$routes->admin . '/login_supervisor'] = 'common_controller/admin_login';
/*navigation routes*/
$route[$routes->admin . '/navigation'] = 'admin_controller/navigation';
/*slider routes*/
$route[$routes->admin . '/slider'] = 'admin_controller/slider';
$route[$routes->admin . '/update-slider-item/(:num)'] = 'admin_controller/update_slider_item/$1';
/*page routes*/
$route[$routes->admin] = 'admin_controller/index';
$route[$routes->admin . '/settings'] = 'admin_controller/settings';
$route[$routes->admin . '/email-settings'] = 'admin_controller/email_settings';
$route[$routes->admin . '/social-login'] = 'admin_controller/social_login_settings';

$route[$routes->admin . '/add-page'] = 'page_controller/add_page';
$route[$routes->admin . '/update-page'] = 'page_controller/update_page';
$route[$routes->admin . '/pages'] = 'page_controller/pages';
$route[$routes->admin . '/pages'] = 'page_controller/pages';
/*order routes*/
$route[$routes->admin . '/orders'] = 'order_admin_controller/orders';
$route[$routes->admin . '/order-details/(:num)'] = 'order_admin_controller/order_details/$1';
$route[$routes->admin . '/transactions'] = 'order_admin_controller/transactions';
$route[$routes->admin . '/order-bank-transfers'] = 'order_admin_controller/order_bank_transfers';
$route[$routes->admin . '/invoices'] = 'order_admin_controller/invoices';
$route[$routes->admin . '/digital-sales'] = 'order_admin_controller/digital_sales';
/*product routes*/
$route[$routes->admin . '/products'] = 'product_admin_controller/products';
$route[$routes->admin . '/pending-products'] = 'product_admin_controller/pending_products';
$route[$routes->admin . '/hidden-products'] = 'product_admin_controller/hidden_products';
$route[$routes->admin . '/drafts'] = 'product_admin_controller/drafts';
$route[$routes->admin . '/deleted-products'] = 'product_admin_controller/deleted_products';
$route[$routes->admin . '/product-details/(:num)'] = 'product_admin_controller/product_details/$1';
/*featured product routes*/
$route[$routes->admin . '/featured-products'] = 'product_admin_controller/featured_products';
$route[$routes->admin . '/featured-products-transactions'] = 'product_admin_controller/featured_products_transactions';
$route[$routes->admin . '/featured-products-pricing'] = 'product_admin_controller/featured_products_pricing';
/*Product Submitted*/
$route[$routes->admin . '/product_submitted'] = 'admin_controller/product_submitted';
/*Seller Register*/
$route[$routes->admin . '/seller_register'] = 'admin_controller/seller_register';
/*bidding system*/
$route[$routes->admin . '/quote-requests'] = 'admin_controller/quote_requests';
/*page routes*/
$route[$routes->admin . '/pages'] = 'page_controller/pages';
$route[$routes->admin . '/update-page/(:num)'] = 'page_controller/update_page/$1';
/*category routes*/
$route[$routes->admin . '/add-category'] = 'category_controller/add_category';
$route[$routes->admin . '/categories'] = 'category_controller/categories';
$route[$routes->admin . '/update-category/(:num)'] = 'category_controller/update_category/$1';
$route[$routes->admin . '/update-subcategory/(:num)'] = 'category_controller/update_subcategory/$1';
$route[$routes->admin . '/subcategories'] = 'category_controller/subcategories';
$route[$routes->admin . '/add-subcategory'] = 'category_controller/add_subcategory';
/*custom fields*/
$route[$routes->admin . '/add-custom-field'] = 'category_controller/add_custom_field';
$route[$routes->admin . '/custom-fields'] = 'category_controller/custom_fields';
$route[$routes->admin . '/update-custom-field/(:num)'] = 'category_controller/update_custom_field/$1';
$route[$routes->admin . '/custom-field-options/(:num)'] = 'category_controller/custom_field_options/$1';
/*earnings*/
$route[$routes->admin . '/earnings'] = 'earnings_admin_controller/earnings';
$route[$routes->admin . '/completed-payouts'] = 'earnings_admin_controller/completed_payouts';
$route[$routes->admin . '/payout-requests'] = 'earnings_admin_controller/payout_requests';
$route[$routes->admin . '/payout-settings'] = 'earnings_admin_controller/payout_settings';
$route[$routes->admin . '/add-payout'] = 'earnings_admin_controller/add_payout';
$route[$routes->admin . '/seller-balances'] = 'earnings_admin_controller/seller_balances';
$route[$routes->admin . '/update-seller-balance/(:num)'] = 'earnings_admin_controller/update_seller_balance/$1';
/*blog routes*/
$route[$routes->admin . '/blog-add-post'] = 'blog_controller/add_post';
$route[$routes->admin . '/blog-posts'] = 'blog_controller/posts';
$route[$routes->admin . '/update-blog-post/(:num)'] = 'blog_controller/update_post/$1';
$route[$routes->admin . '/blog-categories'] = 'blog_controller/categories';
$route[$routes->admin . '/update-blog-category/(:num)'] = 'blog_controller/update_category/$1';
/*comment routes*/
$route[$routes->admin . '/pending-product-comments'] = 'product_admin_controller/pending_comments';
$route[$routes->admin . '/product-comments'] = 'product_admin_controller/comments';
$route[$routes->admin . '/pending-blog-comments'] = 'blog_controller/pending_comments';
$route[$routes->admin . '/blog-comments'] = 'blog_controller/comments';
/*review routes*/
$route[$routes->admin . '/reviews'] = 'product_admin_controller/reviews';
/*ad spaces routes*/
$route[$routes->admin . '/ad-spaces'] = 'admin_controller/ad_spaces';
/*seo tools routes*/
$route[$routes->admin . '/seo-tools'] = 'admin_controller/seo_tools';
/*location*/
$route[$routes->admin . '/location-settings'] = 'admin_controller/location_settings';
$route[$routes->admin . '/countries'] = 'admin_controller/countries';
$route[$routes->admin . '/states'] = 'admin_controller/states';
$route[$routes->admin . '/add-country'] = 'admin_controller/add_country';
$route[$routes->admin . '/update-country/(:num)'] = 'admin_controller/update_country/$1';
$route[$routes->admin . '/add-state'] = 'admin_controller/add_state';
$route[$routes->admin . '/update-state/(:num)'] = 'admin_controller/update_state/$1';
$route[$routes->admin . '/cities'] = 'admin_controller/cities';
$route[$routes->admin . '/add-city'] = 'admin_controller/add_city';
$route[$routes->admin . '/update-city/(:num)'] = 'admin_controller/update_city/$1';
/*Log Activity*/
$route[$routes->admin . '/log_activity'] = 'admin_controller/log_activity';
/*users routes*/
$route[$routes->admin . '/members'] = 'admin_controller/members';
$route[$routes->admin . '/vendors'] = 'admin_controller/vendors';
$route[$routes->admin . '/administrators'] = 'admin_controller/administrators';
$route[$routes->admin . '/shop-opening-requests'] = 'admin_controller/shop_opening_requests';
$route[$routes->admin . '/add-administrator'] = 'admin_controller/add_administrator';
$route[$routes->admin . '/edit-user/(:num)'] = 'admin_controller/edit_user/$1';

$route[$routes->admin . '/cache-system'] = 'admin_controller/cache_system';
$route[$routes->admin . '/storage'] = 'admin_controller/storage';
/*languages routes*/
$route[$routes->admin . '/languages'] = 'language_controller/languages';
$route[$routes->admin . '/update-language/(:num)'] = 'language_controller/update_language/$1';
$route[$routes->admin . '/translations/(:num)'] = 'language_controller/update_translations/$1';
$route[$routes->admin . '/search-phrases'] = 'language_controller/search_phrases';
/*payment routes*/
$route[$routes->admin . '/payment-settings'] = 'settings_controller/payment_settings';
$route[$routes->admin . '/visual-settings'] = 'admin_controller/visual_settings';
$route[$routes->admin . '/system-settings'] = 'admin_controller/system_settings';
/*currency*/
$route[$routes->admin . '/currency-settings'] = 'admin_controller/currency_settings';
$route[$routes->admin . '/update-currency/(:num)'] = 'admin_controller/update_currency/$1';
//newsletter
$route[$routes->admin . '/send-email-subscribers'] = 'admin_controller/send_email_subscribers';
$route[$routes->admin . '/subscribers'] = 'admin_controller/subscribers';

$route[$routes->admin . '/contact-messages'] = 'admin_controller/contact_messages';
$route[$routes->admin . '/preferences'] = 'admin_controller/preferences';

//form settings
$route[$routes->admin . '/form-settings'] = 'settings_controller/form_settings';
$route[$routes->admin . '/form-settings/shipping-options'] = 'settings_controller/shipping_options';
$route[$routes->admin . '/form-settings/edit-shipping-option/(:num)'] = 'settings_controller/edit_shipping_option/$1';
$route[$routes->admin . '/form-settings/product-conditions'] = 'settings_controller/product_conditions';
$route[$routes->admin . '/form-settings/edit-product-condition/(:num)'] = 'settings_controller/edit_product_condition/$1';

$route[$routes->admin . '/font-settings'] = 'settings_controller/font_settings';
$route[$routes->admin . '/update-font/(:num)'] = 'settings_controller/update_font/$1';
$route[$routes->admin . '/route-settings'] = 'settings_controller/route_settings';

//Supervisor
$route[$routes->admin . '/supervisor'] = 'admin_supervisor_controller/index';

//Partners
$route[$routes->admin . '/partners'] = 'admin_partners_controller/index';



$route['(:any)/(:any)']['GET'] = 'home_controller/subcategory/$1/$2';
$route['(:any)']['GET'] = 'home_controller/any/$1';





