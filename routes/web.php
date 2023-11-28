<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ScrapController;
use App\Http\Controllers\Admin\SettingsController;

use App\Http\Controllers\Public\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Admin*/
Route::get('/admin', [AuthController::class, 'index'])->middleware('guest');
Route::post('/admin', [AuthController::class, 'login'])->name('login');
Route::post('/admin/logout', [AuthController::class, 'logout']);

Route::middleware(['auth', 'admin'])->group(function () {
	Route::post('/admin/dashboard/scrap', [ScrapController::class, 'scrap'])->name('admin.scrap');

	Route::get('/admin/dashboard/navbar-settings', [SettingsController::class, 'index'])->name('admin.settings');
	Route::get('/admin/dashboard/navbar-settings/add', [SettingsController::class, 'navbar_add'])->name('admin.navbar_add');
	Route::get('/admin/dashboard/navbar-settings/edit/{id}', [SettingsController::class, 'navbar_edit'])->name('admin.navbar_edit');
	Route::post('/admin/dashboard/navbar-settings/action/{id}', [SettingsController::class, 'navbar_process'])->name('admin.navbar_process');
	Route::delete('/admin/dashboard/navbar-settings/delete/{id}', [SettingsController::class, 'navbar_delete'])->name('admin.navbar_delete');

	Route::get('/admin/dashboard', [ContentController::class, 'index'])->name('admin.dashboard');
	Route::get('/admin/dashboard/content-detail/{id}', [ContentController::class, 'content_detail'])->name('admin.dashboard.content_detail');
	Route::post('/admin/dashboard/content-detail/scrap', [ContentController::class, 'content_detail_scrap'])->name('admin.dashboard.content_detail_scrap');
	Route::get('/admin/dashboard/content/edit/{id}', [ContentController::class, 'content_edit'])->name('admin.dashboard.content_edit');
	Route::post('/admin/dashboard/content/edit/{id}', [ContentController::class, 'content_edit_proccess'])->name('admin.dashboard.content_edit_proccess');

	Route::get('/admin/dashboard/category', [CategoryController::class, 'index'])->name('admin.category');
	Route::post('/admin/dashboard/category/scrap', [CategoryController::class, 'scrap'])->name('admin.category.scrap');

	Route::get('/admin/dashboard/tag', [TagController::class, 'index'])->name('admin.tag');
	Route::post('/admin/dashboard/tag/scrap', [TagController::class, 'scrap'])->name('admin.tag.scrap');
});
/*Admin*/


/*Public*/
Route::get('/', [HomeController::class, 'index'])->name('public.home');
Route::get('/all', [HomeController::class, 'show_all'])->name('public.all');
Route::get('/content/{id}/detail/{title}', [HomeController::class, 'content_detail'])->name('public.content_detail');

Route::get('/football', [HomeController::class, 'football'])->name('public.football');
Route::get('/football/all', [HomeController::class, 'football_show_all'])->name('public.football_all');
Route::get('/moto-gp', [HomeController::class, 'motogp'])->name('public.motogp');
Route::get('/moto-gp/all/', [HomeController::class, 'motogp_show_all'])->name('public.motogp_all');
/*Public*/
