<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\InvestmentController;
use App\Http\Controllers\API\FundController;
use App\Http\Controllers\API\GoalController;
use App\Http\Controllers\API\InsightController;
use App\Http\Controllers\API\HelpController;
use App\Http\Controllers\API\version_2\AuthController2;
use App\Http\Controllers\API\version_2\CustomerController2;
use App\Http\Controllers\API\version_2\InvestmentController2;
use App\Http\Controllers\API\version_2\FundController2;
use App\Http\Controllers\API\version_2\GoalController2;
use App\Http\Controllers\API\version_2\InsightController2;
use App\Http\Controllers\API\version_2\HelpController2;
use App\Http\Controllers\API\version_2\UnitStatementController;
use App\Http\Controllers\API\version_2\ConversionController;

use App\Http\Controllers\API\version_3\AuthController3;
use App\Http\Controllers\API\version_3\CustomerController3;
use App\Http\Controllers\API\version_3\InvestmentController3;
use App\Http\Controllers\API\version_3\FundController3;
use App\Http\Controllers\API\version_3\GoalController3;
use App\Http\Controllers\API\version_3\InsightController3;
use App\Http\Controllers\API\version_3\HelpController3;
use App\Http\Controllers\API\version_3\UnitStatementController3;
use App\Http\Controllers\API\version_3\ConversionController3;

use App\Http\Controllers\API\version_4\AuthController4;
use App\Http\Controllers\API\version_4\CustomerController4;
use App\Http\Controllers\API\version_4\InvestmentController4;
use App\Http\Controllers\API\version_4\FundController4;
use App\Http\Controllers\API\version_4\GoalController4;
use App\Http\Controllers\API\version_4\InsightController4;
use App\Http\Controllers\API\version_4\HelpController4;
use App\Http\Controllers\API\version_4\UnitStatementController4;
use App\Http\Controllers\API\version_4\ConversionController4;
use App\Http\Controllers\API\version_4\YPayAcademyController4;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// VERSION 1 ROUTES
Route::post('/login', [AuthController::class, 'login']);
Route::post('/saveFcm', [AuthController::class, 'saveFcm']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/otp-login', [AuthController::class, 'otpLogin']);
Route::post('/otp-verify', [AuthController::class, 'otpVerify']);
Route::post('/ref-user', [AuthController::class, 'refUser']);
Route::post('/desktop-login', [AuthController::class, 'desktopLogin']);
Route::post('/set-pwd', [AuthController::class, 'setPwd']);
Route::post('/get-profile', [AuthController::class, 'getProfile']);
Route::post('/get-conf-value', [AuthController::class, 'get_conf_val']);
Route::post('/notifications', [AuthController::class, 'getNotification']);
Route::post('/notification-read', [AuthController::class, 'notificationRead']);
Route::post('/mark-notification-read', [AuthController::class, 'markindvidualRead']);
Route::post('/mark-all-notification-read', [AuthController::class, 'markallRead']);
Route::post('/check-email-and-number', [AuthController::class, 'check_email_and_number']);
Route::post('/forgot-pin-send-mail', [AuthController::class, 'forgot_pin_send_mail']);
Route::post('/change-pin', [AuthController::class, 'change_pin']);
Route::post('/change-pin-and-number', [AuthController::class, 'change_pin_and_number']);
Route::post('/verify-pin', [AuthController::class, 'verify_pin']);

// Route::post('/delete-profile-data',[CustomerController::class, 'delete_users_profile']);
Route::post('/profile', [CustomerController::class, 'profile']);
Route::get('/get_risk_profile_questions', [CustomerController::class, 'get_risk_profile_questions']);
Route::post('/high_risk', [CustomerController::class, 'high_risk']);
Route::post('/risk-profile', [CustomerController::class, 'risk_profile']);
Route::post('/get-otp-mobile', [CustomerController::class, 'getOtpMobile']);
Route::post('/otp-verify-mobile', [CustomerController::class, 'otpVerifyMobile']);
Route::get('/states', [CustomerController::class, 'states']);
Route::post('/cities', [CustomerController::class, 'cities']);
Route::get('/countries', [CustomerController::class, 'countries']);
Route::get('/occupations', [CustomerController::class, 'occupations']);
Route::post('/store-profile-image', [CustomerController::class, 'store_profile_image']);
Route::post('/get-profile-image', [CustomerController::class, 'get_profile_image']);
Route::get('/banks', [CustomerController::class, 'banks']);
Route::get('/income_sources', [CustomerController::class, 'income_sources']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/registerVerify', [AuthController::class, 'registerVerify']);
Route::post('/reset-pwd', [AuthController::class, 'resetPwd']);
Route::post('/update-signature', [AuthController::class, 'update_signature']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/resendSignUpOtp', [AuthController::class, 'resendSignUpOtp']);
Route::post('/change-password',[AuthController::class,'changePassword']);
Route::post('/check-email',[AuthController::class,'checkemail']);
Route::post('/check-number',[AuthController::class,'check_phone_no']);

Route::post('/investment', [InvestmentController::class, 'save']);
Route::post('/get-amc', [InvestmentController::class, 'getAmc']);
Route::post('/show-investment-fund', [InvestmentController::class, 'showInvestmentFunds']);
Route::post('/show-investment', [InvestmentController::class, 'showInvestments']);
Route::post('/get-users-investment_count', [InvestmentController::class, 'getuserinvestmentcount']);
Route::post('/show-single-investment', [InvestmentController::class, 'showSingleInvestments']);
Route::post('/show-single-redemption', [InvestmentController::class, 'showSingleRedemptions']);
Route::post('/redemption', [InvestmentController::class, 'addRedemption']);
Route::post('/checkAmcProfile', [InvestmentController::class, 'checkAmcProfile']);
// Route::post('/funds', [FundController::class, 'resetPwd']);
Route::get('/funds/amc', [FundController::class, 'amcIndex']);
Route::post('/funds/popular', [FundController::class, 'isPopular']);
Route::post('/funds/popular_and_new', [FundController::class, 'get_popular_and_new']);
Route::post('/funds/all', [FundController::class, 'isAll']);
Route::get('/funds/amc/{id}', [FundController::class, 'amcShow']);
Route::get('/funds/{id}', [FundController::class, 'show']);
Route::post('/get-all-goals', [GoalController::class, 'getAllGoals']);
Route::post('/get-goal-cat', [GoalController::class, 'getCategories']);
Route::post('/save-goal', [GoalController::class, 'save']);
Route::post('/searchFund',[InvestmentController::class,'searchViaFundName']);
Route::post('/myTransactionHistory',[InvestmentController::class,'myTransactionHistory']);
Route::post('/searchFunds',[FundController::class, 'searchFund']);
Route::post('/searchInvestment',[InvestmentController::class, 'searchInvestment']);

Route::post('/insights', [InsightController::class, 'show']);
Route::post('/insight-filter', [InsightController::class, 'filter']);

Route::post('/getUnitStatement', [UnitStatementController::class, 'getUnitStatement']);

Route::post('/faq', [HelpController::class, 'getFaq']);
Route::post('/policy', [HelpController::class, 'getPolicy']);
Route::post('/searchFaq', [HelpController::class, 'searchFaq']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    // Route::get('/user', [AuthController::class, 'login']);

});

// VERSION 2 ROUTES
Route::post('/v2/login', [AuthController2::class, 'login']);
Route::post('/v2/check-email-and-number', [AuthController2::class, 'check_email_and_number']);
Route::post('/v2/forgot-pin-send-mail', [AuthController2::class, 'forgot_pin_send_mail']);
Route::post('/v2/change-pin', [AuthController2::class, 'change_pin']);
Route::post('/v2/change-pin-and-number', [AuthController2::class, 'change_pin_and_number']);
Route::post('/v2/verify-pin', [AuthController2::class, 'verify_pin']);
Route::post('/v2/otp-login', [AuthController2::class, 'otpLogin']);
Route::post('/v2/otp-verify', [AuthController2::class, 'otpVerify']);
// Route::post('/v2/delete-profile-data',[CustomerController2::class, 'delete_users_profile']);
Route::post('/v2/register', [AuthController2::class, 'register']);
Route::post('/v2/registerVerify', [AuthController2::class, 'registerVerify']);
Route::post('/v2/reset-pwd', [AuthController2::class, 'resetPwd']);
Route::post('/v2/update-signature', [AuthController2::class, 'update_signature']);
Route::post('/v2/resend-otp', [AuthController2::class, 'resendOtp']);
Route::post('/v2/resendSignUpOtp', [AuthController2::class, 'resendSignUpOtp']);
Route::post('/v2/change-password',[AuthController2::class,'changePassword']);
Route::post('/v2/check-email',[AuthController2::class,'checkemail']);
Route::post('/v2/check-number',[AuthController2::class,'check_phone_no']);
Route::post('/v2/get-otp-mobile', [CustomerController2::class, 'getOtpMobile']);
Route::post('/v2/otp-verify-mobile', [CustomerController2::class, 'otpVerifyMobile']);
Route::group(['middleware' => ['auth', 'api', 'auth:api']], function () {
Route::post('/v2/saveFcm', [AuthController2::class, 'saveFcm']);
Route::post('/v2/logout', [AuthController2::class, 'logout']);
Route::post('/v2/ref-user', [AuthController2::class, 'refUser']);
Route::post('/v2/desktop-login', [AuthController2::class, 'desktopLogin']);
Route::post('/v2/set-pwd', [AuthController2::class, 'setPwd']);
Route::post('/v2/get-profile', [AuthController2::class, 'getProfile']);
Route::post('/v2/get-conf-value', [AuthController2::class, 'get_conf_val']);
Route::post('/v2/notifications', [AuthController2::class, 'getNotification']);
Route::post('/v2/notification-read', [AuthController2::class, 'notificationRead']);
Route::post('/v2/mark-notification-read', [AuthController2::class, 'markindvidualRead']);
Route::post('/v2/mark-all-notification-read', [AuthController2::class, 'markallRead']);
Route::post('/v2/profile', [CustomerController2::class, 'profile']);
Route::get('/v2/get_risk_profile_questions', [CustomerController2::class, 'get_risk_profile_questions']);
Route::post('/v2/high_risk', [CustomerController2::class, 'high_risk']);
Route::post('/v2/risk-profile', [CustomerController2::class, 'risk_profile']);
Route::get('/v2/states', [CustomerController2::class, 'states']);
Route::post('/v2/cities', [CustomerController2::class, 'cities']);
Route::get('/v2/countries', [CustomerController2::class, 'countries']);
Route::get('/v2/occupations', [CustomerController2::class, 'occupations']);
Route::post('/v2/store-profile-image', [CustomerController2::class, 'store_profile_image']);
Route::post('/v2/get-profile-image', [CustomerController2::class, 'get_profile_image']);
Route::get('/v2/banks', [CustomerController2::class, 'banks']);
Route::get('/v2/income_sources', [CustomerController2::class, 'income_sources']);

Route::post('/v2/investment', [InvestmentController2::class, 'save']);
Route::post('/v2/get-amc', [InvestmentController2::class, 'getAmc']);
Route::post('/v2/show-investment-fund', [InvestmentController2::class, 'showInvestmentFunds']);
Route::post('/v2/show-investment', [InvestmentController2::class, 'showInvestments']);
Route::post('/v2/get-users-investment_count', [InvestmentController2::class, 'getuserinvestmentcount']);
Route::post('/v2/show-single-investment', [InvestmentController2::class, 'showSingleInvestments']);
Route::post('/v2/show-single-redemption', [InvestmentController2::class, 'showSingleRedemptions']);
Route::post('/v2/redemption', [InvestmentController2::class, 'addRedemption']);
Route::post('/v2/checkAmcProfile', [InvestmentController2::class, 'checkAmcProfile']);
Route::post('/v2/addAmcProfile', [InvestmentController2::class, 'addAmcProfile']);
// Route::post('/funds', [FundController::class, 'resetPwd']);
Route::get('/v2/funds/amc', [FundController2::class, 'amcIndex']);
Route::post('/v2/funds/popular', [FundController2::class, 'isPopular']);
Route::post('/v2/funds/popular_and_new', [FundController2::class, 'get_popular_and_new']);
Route::post('/v2/funds/all', [FundController2::class, 'isAll']);
Route::get('/v2/funds/amc/{id}', [FundController2::class, 'amcShow']);
Route::get('/v2/funds/{id}', [FundController2::class, 'show']);
Route::post('/v2/get-all-goals', [GoalController2::class, 'getAllGoals']);
Route::post('/v2/get-goal-cat', [GoalController2::class, 'getCategories']);
Route::post('/v2/save-goal', [GoalController2::class, 'save']);
Route::post('/v2/searchFund',[InvestmentController2::class,'searchViaFundName']);
Route::post('/v2/myTransactionHistory',[InvestmentController2::class,'myTransactionHistory']);
Route::post('/v2/searchFunds',[FundController2::class, 'searchFund']);
Route::post('/v2/searchInvestment',[InvestmentController2::class, 'searchInvestment']);

Route::post('/v2/insights', [InsightController2::class, 'show']);
Route::post('/v2/insight-filter', [InsightController2::class, 'filter']);


Route::post('/v2/faq', [HelpController2::class, 'getFaq']);
Route::post('/v2/policy', [HelpController2::class, 'getPolicy']);
Route::post('/v2/searchFaq', [HelpController2::class, 'searchFaq']);
Route::post('/v2/get_portfolio_data', [UnitStatementController::class, 'get_portfolio_data']);
Route::post('/v2/getUnitStatement', [UnitStatementController::class, 'getUnitStatement']);

// conversion
Route::post('/v2/conversion', [ConversionController::class, 'save']);
});


// VERSION 3 ROUTES
Route::post('/v3/login', [AuthController3::class, 'login']);
Route::post('/v3/check-email-and-number', [AuthController3::class, 'check_email_and_number']);
Route::post('/v3/forgot-pin-send-mail', [AuthController3::class, 'forgot_pin_send_mail']);
Route::post('/v3/change-pin', [AuthController3::class, 'change_pin']);
Route::post('/v3/change-pin-and-number', [AuthController3::class, 'change_pin_and_number']);
Route::post('/v3/verify-pin', [AuthController3::class, 'verify_pin']);
Route::post('/v3/otp-login', [AuthController3::class, 'otpLogin']);
Route::post('/v3/otp-verify', [AuthController3::class, 'otpVerify']);
Route::post('/v3/delete-profile-data',[CustomerController3::class, 'delete_users_profile']);
Route::post('/v3/register', [AuthController3::class, 'register']);
Route::post('/v3/registerVerify', [AuthController3::class, 'registerVerify']);
Route::post('/v3/reset-pwd', [AuthController3::class, 'resetPwd']);
Route::post('/v3/update-signature', [AuthController3::class, 'update_signature']);
Route::post('/v3/resend-otp', [AuthController3::class, 'resendOtp']);
Route::post('/v3/resendSignUpOtp', [AuthController3::class, 'resendSignUpOtp']);
Route::post('/v3/change-password',[AuthController3::class,'changePassword']);
Route::post('/v3/check-email',[AuthController3::class,'checkemail']);
Route::post('/v3/check-number',[AuthController3::class,'check_phone_no']);
Route::post('/v3/get-otp-mobile', [CustomerController3::class, 'getOtpMobile']);
Route::post('/v3/otp-verify-mobile', [CustomerController3::class, 'otpVerifyMobile']);
Route::group(['middleware' => ['auth', 'api', 'auth:api']], function () {
Route::post('/v3/saveFcm', [AuthController3::class, 'saveFcm']);
Route::post('/v3/logout', [AuthController3::class, 'logout']);
Route::post('/v3/ref-user', [AuthController3::class, 'refUser']);
Route::post('/v3/desktop-login', [AuthController3::class, 'desktopLogin']);
Route::post('/v3/set-pwd', [AuthController3::class, 'setPwd']);
Route::post('/v3/get-profile', [AuthController3::class, 'getProfile']);
Route::post('/v3/get-conf-value', [AuthController3::class, 'get_conf_val']);
Route::post('/v3/notifications', [AuthController3::class, 'getNotification']);
Route::post('/v3/notification-read', [AuthController3::class, 'notificationRead']);
Route::post('/v3/mark-notification-read', [AuthController3::class, 'markindvidualRead']);
Route::post('/v3/mark-all-notification-read', [AuthController3::class, 'markallRead']);
Route::post('/v3/profile', [CustomerController3::class, 'profile']);
Route::post('/v3/edit-profile', [CustomerController3::class, 'edit_profile']);
Route::get('/v3/get_risk_profile_questions', [CustomerController3::class, 'get_risk_profile_questions']);
Route::post('/v3/high_risk', [CustomerController3::class, 'high_risk']);
Route::post('/v3/risk-profile', [CustomerController3::class, 'risk_profile']);
Route::get('/v3/states', [CustomerController3::class, 'states']);
Route::post('/v3/cities', [CustomerController3::class, 'cities']);
Route::get('/v3/countries', [CustomerController3::class, 'countries']);
Route::get('/v3/occupations', [CustomerController3::class, 'occupations']);
Route::post('/v3/store-profile-image', [CustomerController3::class, 'store_profile_image']);
Route::post('/v3/get-profile-image', [CustomerController3::class, 'get_profile_image']);
Route::get('/v3/banks', [CustomerController3::class, 'banks']);
Route::get('/v3/income_sources', [CustomerController3::class, 'income_sources']);

Route::post('/v3/investment', [InvestmentController3::class, 'save']);
Route::post('/v3/get-amc', [InvestmentController3::class, 'getAmc']);
Route::post('/v3/show-investment-fund', [InvestmentController3::class, 'showInvestmentFunds']);
Route::post('/v3/show-investment', [InvestmentController3::class, 'showInvestments']);
Route::post('/v3/get-users-investment_count', [InvestmentController3::class, 'getuserinvestmentcount']);
Route::post('/v3/show-single-investment', [InvestmentController3::class, 'showSingleInvestments']);
Route::post('/v3/show-single-redemption', [InvestmentController3::class, 'showSingleRedemptions']);
Route::post('/v3/redemption', [InvestmentController3::class, 'addRedemption']);
Route::post('/v3/checkAmcProfile', [InvestmentController3::class, 'checkAmcProfile']);
Route::post('/v3/addAmcProfile', [InvestmentController3::class, 'addAmcProfile']);
// Route::post('/funds', [FundController::class, 'resetPwd']);
Route::get('/v3/funds/amc', [FundController3::class, 'amcIndex']);
Route::post('/v3/funds/popular', [FundController3::class, 'isPopular']);
Route::post('/v3/funds/popular_and_new', [FundController3::class, 'get_popular_and_new']);
Route::post('/v3/funds/all', [FundController3::class, 'isAll']);
Route::get('/v3/funds/amc/{id}', [FundController3::class, 'amcShow']);
Route::get('/v3/funds/{id}', [FundController3::class, 'show']);
Route::post('/v3/get-all-goals', [GoalController3::class, 'getAllGoals']);
Route::post('/v3/get-goal-cat', [GoalController3::class, 'getCategories']);
Route::post('/v3/save-goal', [GoalController3::class, 'save']);
Route::post('/v3/searchFund',[InvestmentController3::class,'searchViaFundName']);
Route::post('/v3/myTransactionHistory',[InvestmentController3::class,'myTransactionHistory']);
Route::post('/v3/searchFunds',[FundController3::class, 'searchFund']);
Route::post('/v3/searchInvestment',[InvestmentController3::class, 'searchInvestment']);

Route::post('/v3/insights', [InsightController3::class, 'show']);
Route::post('/v3/insight-filter', [InsightController3::class, 'filter']);


Route::post('/v3/faq', [HelpController3::class, 'getFaq']);
Route::post('/v3/policy', [HelpController3::class, 'getPolicy']);
Route::post('/v3/searchFaq', [HelpController3::class, 'searchFaq']);
Route::post('/v3/get_portfolio_data', [UnitStatementController3::class, 'get_portfolio_data']);
Route::post('/v3/getUnitStatement', [UnitStatementController3::class, 'getUnitStatement']);

// conversion
Route::post('/v3/conversion', [ConversionController3::class, 'save']);
});

// VERSION 4 ROUTES
Route::post('/v4/login', [AuthController4::class, 'login']);
Route::post('/v4/check-email-and-number', [AuthController4::class, 'check_email_and_number']);
Route::post('/v4/forgot-pin-send-mail', [AuthController4::class, 'forgot_pin_send_mail']);
Route::post('/v4/change-pin', [AuthController4::class, 'change_pin']);
Route::post('/v4/change-pin-and-number', [AuthController4::class, 'change_pin_and_number']);
Route::post('/v4/verify-pin', [AuthController4::class, 'verify_pin']);
Route::post('/v4/otp-login', [AuthController4::class, 'otpLogin']);
Route::post('/v4/otp-verify', [AuthController4::class, 'otpVerify']);
Route::post('/v4/delete-profile-data',[CustomerController4::class, 'delete_users_profile']);
Route::post('/v4/register', [AuthController4::class, 'register']);
Route::post('/v4/registerVerify', [AuthController4::class, 'registerVerify']);
Route::post('/v4/reset-pwd', [AuthController4::class, 'resetPwd']);
Route::post('/v4/update-signature', [AuthController4::class, 'update_signature']);
Route::post('/v4/resend-otp', [AuthController4::class, 'resendOtp']);
Route::post('/v4/resendSignUpOtp', [AuthController4::class, 'resendSignUpOtp']);
Route::post('/v4/change-password',[AuthController4::class,'changePassword']);
Route::post('/v4/check-email',[AuthController4::class,'checkemail']);
Route::post('/v4/check-number',[AuthController4::class,'check_phone_no']);
Route::post('/v4/get-otp-mobile', [CustomerController4::class, 'getOtpMobile']);
Route::post('/v4/otp-verify-mobile', [CustomerController4::class, 'otpVerifyMobile']);
Route::group(['middleware' => ['auth', 'api', 'auth:api']], function () {
Route::post('/v4/saveFcm', [AuthController4::class, 'saveFcm']);
Route::post('/v4/logout', [AuthController4::class, 'logout']);
Route::post('/v4/ref-user', [AuthController4::class, 'refUser']);
Route::post('/v4/desktop-login', [AuthController4::class, 'desktopLogin']);
Route::post('/v4/set-pwd', [AuthController4::class, 'setPwd']);
Route::post('/v4/get-profile', [AuthController4::class, 'getProfile']);
Route::post('/v4/get-conf-value', [AuthController4::class, 'get_conf_val']);
Route::post('/v4/notifications', [AuthController4::class, 'getNotification']);
Route::post('/v4/notification-read', [AuthController4::class, 'notificationRead']);
Route::post('/v4/mark-notification-read', [AuthController4::class, 'markindvidualRead']);
Route::post('/v4/mark-all-notification-read', [AuthController4::class, 'markallRead']);
Route::post('/v4/profile', [CustomerController4::class, 'profile']);
Route::post('/v4/edit-profile', [CustomerController4::class, 'edit_profile']);
Route::get('/v4/get_risk_profile_questions', [CustomerController4::class, 'get_risk_profile_questions']);
Route::post('/v4/high_risk', [CustomerController4::class, 'high_risk']);
Route::post('/v4/risk-profile', [CustomerController4::class, 'risk_profile']);
Route::get('/v4/states', [CustomerController4::class, 'states']);
Route::post('/v4/cities', [CustomerController4::class, 'cities']);
Route::get('/v4/countries', [CustomerController4::class, 'countries']);
Route::get('/v4/occupations', [CustomerController4::class, 'occupations']);
Route::post('/v4/store-profile-image', [CustomerController4::class, 'store_profile_image']);
Route::post('/v4/get-profile-image', [CustomerController4::class, 'get_profile_image']);
Route::get('/v4/banks', [CustomerController4::class, 'banks']);
Route::get('/v4/income_sources', [CustomerController4::class, 'income_sources']);

Route::post('/v4/investment', [InvestmentController4::class, 'save']);
Route::post('/v4/get-amc', [InvestmentController4::class, 'getAmc']);
Route::post('/v4/show-investment-fund', [InvestmentController4::class, 'showInvestmentFunds']);
Route::post('/v4/show-investment', [InvestmentController4::class, 'showInvestments']);
Route::post('/v4/get-users-investment_count', [InvestmentController4::class, 'getuserinvestmentcount']);
Route::post('/v4/show-single-investment', [InvestmentController4::class, 'showSingleInvestments']);
Route::post('/v4/show-single-redemption', [InvestmentController4::class, 'showSingleRedemptions']);
Route::post('/v4/redemption', [InvestmentController4::class, 'addRedemption']);
Route::post('/v4/checkAmcProfile', [InvestmentController4::class, 'checkAmcProfile']);
Route::post('/v4/addAmcProfile', [InvestmentController4::class, 'addAmcProfile']);
// Route::post('/funds', [FundController::class, 'resetPwd']);
Route::get('/v4/funds/amc', [FundController4::class, 'amcIndex']);
Route::post('/v4/funds/popular', [FundController4::class, 'isPopular']);
Route::post('/v4/funds/popular_and_new', [FundController4::class, 'get_popular_and_new']);
Route::post('/v4/funds/all', [FundController4::class, 'isAll']);
Route::get('/v4/funds/amc/{id}', [FundController4::class, 'amcShow']);
Route::get('/v4/funds/{id}', [FundController4::class, 'show']);
Route::post('/v4/get-all-goals', [GoalController4::class, 'getAllGoals']);
Route::post('/v4/get-goal-cat', [GoalController4::class, 'getCategories']);
Route::post('/v4/save-goal', [GoalController4::class, 'save']);
Route::post('/v4/searchFund',[InvestmentController4::class,'searchViaFundName']);
Route::post('/v4/myTransactionHistory',[InvestmentController4::class,'myTransactionHistory']);
Route::post('/v4/searchFunds',[FundController4::class, 'searchFund']);
Route::post('/v4/searchInvestment',[InvestmentController4::class, 'searchInvestment']);

Route::post('/v4/insights', [InsightController4::class, 'show']);
Route::post('/v4/insight-filter', [InsightController4::class, 'filter']);

Route::get('/v4/getChapters', [YPayAcademyController4::class, 'getChapters']);
Route::post('/v4/get/progress', [YPayAcademyController4::class, 'get_user_progress']);
Route::post('/v4/save/progress', [YPayAcademyController4::class, 'save_user_progress']);
Route::post('/v4/get_ac_questions', [YPayAcademyController4::class, 'get_ac_questions']);

Route::post('/v4/faq', [HelpController4::class, 'getFaq']);
Route::post('/v4/policy', [HelpController4::class, 'getPolicy']);
Route::post('/v4/searchFaq', [HelpController4::class, 'searchFaq']);
Route::post('/v4/get_portfolio_data', [UnitStatementController4::class, 'get_portfolio_data']);
Route::post('/v4/getUnitStatement', [UnitStatementController4::class, 'getUnitStatement']);

// conversion
Route::post('/v4/conversion', [ConversionController4::class, 'save']);
});
