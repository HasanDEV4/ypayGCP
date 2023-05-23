<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AmcController;
use App\Http\Controllers\AmcDataController;
use App\Http\Controllers\AmcCustProfileController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RequestCustomerController;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\ConversionController;
use App\Http\Controllers\RedemptionController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\InsightController;
use App\Http\Controllers\InsightVideoController;
use App\Http\Controllers\InsightTagController;
use App\Http\Controllers\RiskProfileController;
use App\Http\Controllers\RiskProfileQuestionController;
use App\Http\Controllers\RiskProfileRankController;
use App\Http\Controllers\AmcCountryController;
use App\Http\Controllers\AmcCityController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DividendController;
use App\Http\Controllers\AmcOccupationController;
use App\Http\Controllers\AmcBankController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MISReportController;
use App\Http\Controllers\AmcIncomeController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\AmcFundController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewSignUpController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AcademyChapterController;
use App\Http\Controllers\ChapterQuestionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [AuthController::class,'showLoginForm']);
Route::get('/login', [AuthController::class,'showLoginForm'])->name('login.show');
Route::post('/login', [AuthController::class,'login'])->name('login');
Route::post('/logout', [AuthController::class,'logout'])->name('logout');

Route::group(['middleware' => ['auth','web']], function () {


  Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
  Route::get('/dashboard/getStats', [DashboardController::class, 'getStats'])->name('dashboard.getStats');



  Route::resource('policies', PolicyController::class);
  Route::get('/policies/getData', [PolicyController::class, 'getData'])->name('policies.getData');

  Route::resource('faqs', FaqController::class);
  Route::get('/faqs/getData', [FaqController::class, 'getData'])->name('faqs.getData');

  Route::resource('goal', GoalController::class);
  Route::get('/goal/getData', [GoalController::class, 'getData'])->name('goal.getData');

  // Route::resource('insights', AmcController::class);

  Route::resource('amc', AmcController::class);
  
  Route::get('/add/amc', [AmcController::class, 'AmcAdd'])->name('add.amc');
  Route::get('/edit/amc/{id}', [AmcController::class, 'amcEdit'])->name('edit.amc');
  Route::get('/amc/getData', [AmcController::class, 'getData'])->name('amc.getData');
  Route::get('/autocomplete/amc', [AmcController::class, 'autocomplete'])->name('amc.autocomplete');

  Route::get('/insight/fields', [InsightController::class, 'getFields'])->name('insight.getFields');
  Route::resource('insight', InsightController::class);
  Route::get('/insight/getData', [InsightController::class, 'getData'])->name('insight.getData');
  Route::post('/insight/saveBlog', [InsightController::class, 'saveBlog'])->name('saveBlog');
  Route::put('/insight/updateBlog/{insight}', [InsightController::class, 'updateBlog'])->name('updateBlog');
  
  //AMC Data Route
  Route::resource('amc_data',AmcDataController::class);
  Route::get('/get/amc_data',[AmcDataController::class,'get_amc_data'])->name('get.amc_data');
  
  //Account Types Route
  Route::resource('account_type',AccountTypeController::class);
  Route::get('/account-types',[AccountTypeController::class,'index'])->name('account_types.index');
  Route::get('/account-types/getData', [AccountTypeController::class, 'show'])->name('account_types.getData');

  // risk profile routes
  Route::resource('risk_profile',RiskProfileController::class);
  Route::get('/risk-profile/getData', [RiskProfileController::class, 'show'])->name('risk_profile.getData');
  Route::get('/risk-profile',[RiskProfileController::class,'index'])->name('risk_profile.index');

  // risk profile questions routes
  Route::resource('risk_profile_questions',RiskProfileQuestionController::class);
  Route::get('/risk-profile-questions/getData', [RiskProfileQuestionController::class, 'show'])->name('risk_profile_questions.getData');
  Route::get('/risk-profile-questions',[RiskProfileQuestionController::class,'index'])->name('risk_profile_questions.index');
  Route::get('/categories/autocomplete', [RiskProfileQuestionController::class, 'autocomplete'])->name('categories.autocomplete');
  
// risk profile ranks routes
  Route::resource('risk_profile_ranks',RiskProfileRankController::class);
  Route::get('/risk-profile-ranks/getData', [RiskProfileRankController::class, 'show'])->name('risk_profile_ranks.getData');
  Route::get('/risk-profile-ranks',[RiskProfileRankController::class,'index'])->name('risk_profile_ranks.index');

  // amc countries
  Route::resource('amc_countries',AmcCountryController::class);
  Route::get('/amc-countries/getData', [AmcCountryController::class, 'show'])->name('amc_countries.getData');
  Route::get('/amc-countries',[AmcCountryController::class,'index'])->name('amc_countries.index');
  Route::get('/amc-get-countries',[AmcCountryController::class,'getamccountries'])->name('amc_countries.refresh');

  // amc cities
  Route::resource('amc_cities',AmcCityController::class);
  Route::get('/amc-cities/getData', [AmcCityController::class, 'show'])->name('amc_cities.getData');
  Route::get('/amc-cities',[AmcCityController::class,'index'])->name('amc_cities.index');
  Route::get('/amc-get-cities',[AmcCityController::class,'getamccities'])->name('amc_cities.refresh');

  //cities
  Route::resource('cities',CityController::class);
  Route::get('/cities/getData', [CityController::class, 'show'])->name('cities.getData');
  Route::post('/cities/store', [CityController::class, 'store'])->name('cities.store');
  Route::post('/cities/update', [CityController::class, 'update'])->name('cities.update');
  Route::get('/cities',[CityController::class,'index'])->name('cities.index');
  Route::post('/change-cities-status',[CityController::class,'change_status'])->name('city.status.change');
  Route::get('/city/autocomplete', [CityController::class, 'autocomplete'])->name('city.autocomplete');

  //occupations
  Route::resource('occupations',OccupationController::class);
  Route::get('/occupations/getData', [OccupationController::class, 'show'])->name('occupations.getData');
  Route::post('/occupations/store', [OccupationController::class, 'store'])->name('occupations.store');
  Route::post('/occupations/update', [OccupationController::class, 'update'])->name('occupations.update');
  Route::get('/occupations',[OccupationController::class,'index'])->name('occupations.index');
  Route::post('/change-occupations-status',[OccupationController::class,'change_status'])->name('occupation.status.change');
  Route::get('/occupation/autocomplete', [OccupationController::class, 'autocomplete'])->name('occupation.autocomplete');

  //banks
  Route::resource('banks',BankController::class);
  Route::get('/banks/getData', [BankController::class, 'show'])->name('banks.getData');
  Route::post('/banks/update', [BankController::class, 'update'])->name('banks.update');
  Route::get('/banks',[BankController::class,'index'])->name('banks.index');
  Route::post('/change-banks-status',[BankController::class,'change_status'])->name('bank.status.change');
  Route::get('/bank/autocomplete', [BankController::class, 'autocomplete'])->name('bank.autocomplete');

  //income_sources
  Route::resource('income_sources',IncomeController::class);
  Route::post('/income_sources/update', [IncomeController::class, 'update'])->name('income_sources.update');
  Route::get('/income_sources/getData', [IncomeController::class, 'show'])->name('income_sources.getData');
  Route::get('/income_sources',[IncomeController::class,'index'])->name('income_sources.index');
  Route::post('/change-income_sources-status',[IncomeController::class,'change_status'])->name('income_sources.status.change');
  Route::get('/income_source/autocomplete', [IncomeController::class, 'autocomplete'])->name('income.autocomplete');

  // amc occupations
  Route::resource('amc_occupations',AmcOccupationController::class);
  Route::get('/amc-occupations/getData', [AmcOccupationController::class, 'show'])->name('amc_occupations.getData');
  Route::get('/amc-occupations',[AmcOccupationController::class,'index'])->name('amc_occupations.index');
  Route::get('/amc-get-occupations',[AmcOccupationController::class,'getamcoccupations'])->name('amc_occupations.refresh');

  // amc funds
  Route::resource('amc_funds',AmcFundController::class);
  Route::get('/amc-funds/getData', [AmcFundController::class, 'show'])->name('amc_funds.getData');
  Route::get('/amc-funds',[AmcFundController::class,'index'])->name('amc_funds.index');
  Route::get('/amc-get-funds',[AmcFundController::class,'getamcfunds'])->name('amc_funds.refresh');
  Route::get('/amc-update-funds',[AmcFundController::class,'update_fund_data'])->name('amc_funds.update');
  Route::get('/get-funds-data',[AmcFundController::class,'get_funds_data'])->name('amc_funds.get_funds_data');

  // amc sources of income
  Route::resource('amc_sources_of_income',AmcIncomeController::class);
  Route::get('/amc-sources-of-income/getData', [AmcIncomeController::class, 'show'])->name('amc_sources_of_income.getData');
  Route::get('/amc-sources-of-income',[AmcIncomeController::class,'index'])->name('amc_sources_of_income.index');
  Route::post('/amc-sources-of-income/update', [AmcIncomeController::class, 'update'])->name('sources_of_income.update');
  Route::get('/amc-get-sources-of-income',[AmcIncomeController::class,'getamcincomesources'])->name('amc_income_sources.refresh');

  // amc banks
  Route::resource('amc_banks',AmcBankController::class);
  Route::get('/amc-banks/getData', [AmcBankController::class, 'show'])->name('amc_banks.getData');
  Route::get('/amc-banks',[AmcBankController::class,'index'])->name('amc_banks.index');
  Route::get('/amc-get-banks',[AmcBankController::class,'getamcbanks'])->name('amc_banks.refresh');

  

  // customer amc profile
  Route::resource('custAmcProfile', AmcCustProfileController::class);
  Route::get('/getData/custAmcProfile', [AmcCustProfileController::class,'getData'])->name('custAmcProfile.getData');
  //kyc_process_route 
  Route::get('/custProfile/kyc', [AmcCustProfileController::class,'kyc_process']);
  Route::get('/custProfile/jsil_kyc', [AmcCustProfileController::class,'jsil_kyc_process']);
  Route::get('/kyc/akd', [AmcCustProfileController::class,'akd_kyc']);
  Route::get('/custProfile/inquiry', [AmcCustProfileController::class,'accounts_opening_confirmation']);
  Route::get('/amcLists/custAmcProfile', [AmcCustProfileController::class,'amcList'])->name('custAmcProfile.amcList');
  Route::get('/customerdropdown/custAmcProfile', [AmcCustProfileController::class, 'customerDropDownList'])->name('custAmcProfile.customerdropdown');
  Route::post('/custAmcProfile/verify', [AmcCustProfileController::class, 'verifycustAmcProfile'])->name('verify.custAmcProfile');
  Route::get('/check-eb-jobs/', [AmcCustProfileController::class,'check_eb_jobs']);

  //insight Tags
  Route::resource('insightTag', InsightTagController::class);

  // insight video
  Route::resource('insightVideo', InsightVideoController::class);
  Route::get('/insight/video/getData', [InsightVideoController::class, 'getData'])->name('insight.video.getData');
  Route::post('/insight/video/saveVideo', [InsightVideoController::class, 'saveVideo'])->name('saveVideo');
  Route::put('/insight/video/updateVideo/{insight}', [InsightVideoController::class, 'updateVideo'])->name('updateVideo');

  Route::get('/fund/add', [FundController::class, 'addFund'])->name('fund.add');
  Route::get('/fund-data/index', [FundController::class, 'funds_data_index'])->name('funds_data.index');
  Route::get('/fund/{id}/edit', [FundController::class, 'editFund'])->name('fund.edit');
  Route::post('/funds/update', [FundController::class, 'editFundData'])->name('fund_data.update');
  Route::post('/fund/save', [FundController::class, 'saveFund'])->name('fund.save');
  Route::resource('fund', FundController::class);
  Route::get('/fund/getData', [FundController::class, 'getData'])->name('fund.getData');
  Route::get('funds/refresh',[FundController::class,'refreshfundsData'])->name('funds_data.refresh');
  Route::get('/funds/getfundsData', [FundController::class, 'getfundsData'])->name('funds.getfundsData');
  Route::get('/funds/updatefundsData', [FundController::class, 'update_funds_data'])->name('funds.updatefundsData');
  Route::get('/funds/autocomplete', [FundController::class, 'autocomplete'])->name('funds.autocomplete');
  Route::get('/funds/scrapping', [FundController::class, 'scrapping'])->name('funds.scrapping');

  //Edit Profile
  Route::resource('edit_requests', EditProfileController::class);
  Route::get('/edit_requests/getData', [EditProfileController::class, 'getData'])->name('edit_requests.getData');
  Route::get('/edit_request_status/details', [EditProfileController::class, 'editprofileshow'])->name('edit_requests.geteditstatusData');
  Route::get('/change_request_status/details/{id}', [EditProfileController::class, 'change_request_status'])->name('change_request_status.details');
  Route::post('/edit_requests/update', [EditProfileController::class, 'update'])->name('edit_requests.update');
  Route::post('/edit_requests/change_request_status/update', [EditProfileController::class, 'change_request_status_update'])->name('change_request_status.update');
  Route::post('/edit_request_status/profile/download/', [EditProfileController::class, 'export_profile'])->name('export.request.status.pdf');

  //Customer Routes
  Route::resource('request', RequestCustomerController::class);
  Route::get('/request/getData', [RequestCustomerController::class, 'getData'])->name('request.getData');
  Route::post('/export', [RequestCustomerController::class, 'export'])->name('cust.export');
  Route::get('/transaction_history/{id}', [RequestCustomerController::class, 'transaction_history'])->name('cust.transaction_history');
  Route::get('/get_transaction_history/{id}', [RequestCustomerController::class, 'get_transaction_history_data'])->name('cust.get_transaction_history');
  Route::get('/customer/details/{id}', [CustomerController::class, 'custDetails'])->name('cust.details');
  Route::get('/risk_profile/details/{id}', [CustomerController::class, 'risk_prof_Details'])->name('risk_profile.details');
  Route::get('risk_profile/exportdetails/{id}', [CustomerController::class, 'export_risk_profile_resp'])->name('export.risk_profile.details');
  Route::get('/risk_profile/editdetails/{id}', [CustomerController::class, 'edit_risk_prof_Details'])->name('edit.risk_profile.details');
  Route::post('/customer/details/export-facta', [CustomerController::class, 'export_facta_details'])->name('export.facta.details');
  Route::get('/customer/editfactaDetails/{id}', [CustomerController::class, 'edit_facta_details'])->name('edit.facta.details');
  Route::put('/customer/updatefactaDetails/{id}', [CustomerController::class, 'facta_details_update'])->name('facta.update.details');
  Route::post('/risk_profile/update_response', [CustomerController::class, 'update_response'])->name('update.response');
  Route::get('/customer/editDetails/{id}', [CustomerController::class, 'edit'])->name('cust.edit.details');
  Route::get('/customer/addform', [CustomerController::class, 'addcustform'])->name('cust.add.form');
  Route::put('/customer/add', [CustomerController::class, 'custadd'])->name('cust.add');
  Route::post('/customer/import/profile', [CustomerController::class, 'importprofile'])->name('cust.import.profile');
  Route::put('/customer/updateDetails/{id}', [CustomerController::class, 'custUpdate'])->name('cust.update.details.update');
  Route::post('/customer/details/status', [CustomerController::class, 'custDetailsStatus'])->name('details.status');
  Route::get('/get/comments/{id}', [CustomerController::class, 'get_admin_comments'])->name('get.admin.comments');
  Route::post('/customer/profile/download/', [CustomerController::class, 'export_profile'])->name('export.profile.pdf');
  Route::post('/save/comments', [CustomerController::class, 'save_admin_comments'])->name('save.admin.comments');
  Route::post('/cities', [CustomerController::class, 'cities'])->name('fetch.cities');


  Route::resource('customer', CustomerController::class);
  Route::get('/customer/getData', [CustomerController::class, 'getData'])->name('customer.getData');
  Route::get('/customers/autocomplete',[CustomerController::class, 'autocomplete'])->name('customers.autocomplete');

  Route::resource('redemptions', RedemptionController::class);
  Route::post('/redemption/export', [RedemptionController::class, 'export'])->name('redem.export');
  Route::get('/redemption/process', [RedemptionController::class,'redemption_process']);
  Route::get('/redemption/jsil/process', [RedemptionController::class,'jsil_redemption_process']);
  Route::get('/redemption/inquiry', [RedemptionController::class,'redemption_inquiry']);
  Route::get('/redemptions/getData', [RedemptionController::class, 'getData'])->name('redemptions.getData');
  Route::post('/redemption/send-verified-to-amc', [RedemptionController::class, 'sendtoamc'])->name('send.verified_redemptions');
  Route::post('/redemption/verify', [RedemptionController::class, 'verifyredemption'])->name('verify.redemption');
  Route::post('/redemption/download/selected', [RedemptionController::class, 'export_selected'])->name('export.selected.redemptions');

  Route::resource('conversions', ConversionController::class);
  Route::post('/conversion/update', [ConversionController::class, 'update'])->name('conversions.update');
  Route::get('/conversions/getData', [ConversionController::class, 'getData'])->name('conversions.getData');
  Route::post('/conversion/verify', [ConversionController::class, 'verifyconversion'])->name('verify.conversion');
  Route::post('/conversion/download/selected', [ConversionController::class, 'export_selected'])->name('export.selected.conversions');
  Route::get('/conversion/customerdropdown', [InvestmentController::class, 'customerDropDownList'])->name('conversions.customerdropdown');
  Route::get('/conversion/funddropdown', [InvestmentController::class, 'fundDropDownList'])->name('conversions.funddropdown');

  Route::resource('chapters', AcademyChapterController::class);
  Route::get('/chapters',[AcademyChapterController::class,'index'])->name('chapters.index');
  Route::get('/chapters/getData', [AcademyChapterController::class, 'getData'])->name('chapters.getData');
  Route::post('/chapters/update', [AcademyChapterController::class, 'update'])->name('chapters.update');

  Route::resource('chapter_questions', ChapterQuestionController::class);
  Route::get('/chapter_questions',[ChapterQuestionController::class,'index'])->name('chapter_questions.index');
  Route::get('/chapter_questions/getData', [ChapterQuestionController::class, 'getData'])->name('chapter_questions.getData');
  Route::post('/chapter_questions/update', [ChapterQuestionController::class, 'update'])->name('chapter_questions.update');
  Route::post('/chapter_questions/delete', [ChapterQuestionController::class, 'delete'])->name('chapter_questions.delete');
  Route::post('chapter_questions/option/delete', [ChapterQuestionController::class, 'option_delete'])->name('chapter_questions.option.delete');
  Route::post('/chapter_questions/save', [ChapterQuestionController::class, 'store'])->name('chapter_questions.store');
  Route::get('/get/question/options/{id}', [ChapterQuestionController::class, 'get_question_options'])->name('get.question.options');
  
  Route::resource('investments', InvestmentController::class);
  Route::post('/investment/export', [InvestmentController::class, 'export'])->name('invest.export');
  Route::get('/investments/getData', [InvestmentController::class, 'getData'])->name('investments.getData');
  Route::get('/investment/process', [InvestmentController::class,'investment_process']);
  Route::get('/investment/akd', [InvestmentController::class,'akd_investment']);
  Route::get('/investment/inquiry', [InvestmentController::class,'investment_inquiry']);
  Route::get('/investment/customerdropdown', [InvestmentController::class, 'customerDropDownList'])->name('investment.customerdropdown');
  Route::get('/investment/funddropdown', [InvestmentController::class, 'fundDropDownList'])->name('investment.funddropdown');
  Route::get('/investment/investmentdropdown', [InvestmentController::class, 'investmentDropDownList'])->name('investment.investmentdropdown');
  Route::get('/investment/details/{id}', [InvestmentController::class, 'edit'])->name('investment.details');
  Route::get('/investments/form/download', [InvestmentController::class, 'download_form'])->name('download.investment.form');
  Route::post('/investment/send-verified-to-amc', [InvestmentController::class, 'sendtoamc'])->name('send.verified_investments');
  Route::post('/investment/download/selected', [InvestmentController::class, 'export_selected'])->name('export.selected.investments');
  Route::post('/investment/verify', [InvestmentController::class, 'verifyinvestment'])->name('verify.investment');


  Route::resource('vendors', VendorController::class);
  Route::get('/vendors/getData', [VendorController::class, 'getData'])->name('vendors.getData');
  Route::post('/vendors/activate/whatsapp', [VendorController::class, 'activatewhatsapp'])->name('activate.whatsapp');
  Route::post('/vendors/activate/sms', [VendorController::class, 'activatesms'])->name('activate.sms');
  
  Route::resource('dividend', DividendController::class);
  Route::get('/dividend/getData', [DividendController::class, 'getData'])->name('dividend.getData');
  Route::post('/dividend/import-csv', [DividendController::class, 'import_csv'])->name('import.dividend.csv');
  Route::post('/dividend/add', [DividendController::class, 'add_dividend'])->name('dividend.add');
  Route::post('/dividend/edit', [DividendController::class, 'edit_dividend'])->name('dividend.edit');
  Route::get('/dividend-index', [DividendController::class, 'index'])->name('dividend.index');
  Route::get('/dividend/import/log',[DividendController::class,'csv_log'])->name('dividend.import.log');
  Route::get('/dividend/get/import/log', [DividendController::class, 'getimportlog'])->name('dividend.getimportlog');
  Route::post('/dividend/change/status', [DividendController::class, 'change_selected_status'])->name('dividend.status.change');

  // notification routes
  Route::post('/store-token', [NotificationController::class, 'storeToken'])->name('store.token');
  Route::post('/send-web-notification', [NotificationController::class, 'sendWebNotification'])->name('send.web-notification');

  Route::get('/notification-index', [NotificationController::class, 'notificationIndex'])->name('notification.index');
  Route::get('/filter_user/getData',[NotificationController::class,'getData'])->name('filter_user.getData');
  Route::get('/filter_user/export', [NotificationController::class, 'export'])->name('filter_user.export');
  Route::get('/notification-history', [NotificationController::class, 'notificationHistory'])->name('notification.history');
  Route::get('/notification-list', [NotificationController::class, 'notificationShow'])->name('notification.list');

  Route::get('/notification-create', [NotificationController::class, 'index'])->name('notification.create');
  Route::post('/send-notification', [NotificationController::class, 'sendNotification'])->name('send.notification');
  Route::post('/send-sms', [NotificationController::class, 'send_sms'])->name('send.sms');
  Route::post('/send-otp', [NotificationController::class, 'send_otp'])->name('send.otp');

//MISreportsroutes
Route::resource('mis', MISReportController::class);
Route::get('/mis/report/export', [MISReportController::class, 'export'])->name('mis.export');
Route::get('/mis-index',[MISReportController::class,'index'])->name('mis.index');

  //reportsroutes
Route::resource('reports', ReportController::class);
Route::get('/reports/getData', [ReportController::class, 'getData'])->name('reports.getData');
Route::get('/reports-index',[ReportController::class,'index'])->name('reports.index');
Route::get('generate-pdf', [ReportController::class, 'generatePDF'])->name('generate.pdf');
  //csvroutes
  Route::resource('csvimport', CsvController::class);
  Route::get('/csvimport/getData', [CsvController::class, 'getData'])->name('csvimport.getData');
  Route::get('/import-csv',[CsvController::class,'import_csv'])->name('import.csv');
  Route::post('/import-csvfile',[CsvController::class,'import_file'])->name('import.csvfile');
  // user routes
  Route::resource('/user',UserController::class);
  Route::get('/users/getData',[UserController::class,'getData'])->name('user.getData');
  Route::get('/new-signup/exportData',[UserController::class,'exportUser'])->name('user.exportUser');
  Route::get('/users/change-password',[UserController::class,'changePasswordView'])->name('user.changePassword');
  Route::post('/users/update-password',[UserController::class,'changePassword'])->name('user.updatePassword');
 
  Route::resource('/newsignup',NewSignUpController::class);
  Route::get('/new-signup/getData',[NewSignUpController::class,'getData'])->name('newsignup.getData');
  Route::post('/new-signup/export', [NewSignUpController::class, 'export'])->name('newsignup.export');
  Route::get('/new-signup/autocomplete',[NewSignUpController::class, 'autocomplete'])->name('newsignup.autocomplete');

  Route::resource('/role',RoleController::class);
  Route::get('/role/edit/{id}',[RoleController::class,'edit'])->name('role.edit');
  Route::get('/roles/getData',[RoleController::class,'getData'])->name('role.getData');

  Route::resource('/permission',PermissionController::class);
  Route::get('/permissions/getData',[PermissionController::class,'getData'])->name('permission.getData');

});
