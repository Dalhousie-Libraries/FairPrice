<?php

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
Route::get('api/prices', 'Api\PriceAPIController@pricegraph')->name('PriceGraph')->middleware('auth');
//Route::get('api/prices', 'Api\PriceAPIController@pricegraph')->name('PriceGraphAPI')->middleware('auth');
Route::get('/', function () {
    if(Auth::check()) {
        return redirect()->route('home');
    } else {
        return redirect()->route('login');
    }
});

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::any('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('netidpassword', function() {
    return redirect('https://password.dal.ca/');
})->name('credentialreset'); # Refer Password Reset to Dalhousie Secure Server.

Route::get('liason', function() {
    return redirect('https://libraries.dal.ca/research/subject-liaisons/directory.html');
})->name('liason');

Route::get('helppdf', function() {
	return redirect('https://cdn.dal.ca/content/dam/dalhousie/pdf/library/Library_Administration/collections/using_the_journal_assessment_db.pdf');
})->name('helppdf');

Route::get('help', function() {
    	return view("pages.desktop.help");
})->name('help');

Route::group(['middleware'=> ['auth']], function() {
    Route::get('firsttime', 'UserController@getwizard')->name('user.wizard')->middleware('auth');
    Route::post('firsttime', 'UserController@submit')->name('user.wizard.submit')->middleware('auth');
}); 

Route::group(['middleware'=> ['auth', 'userwizardcheck']], function() {
    Route::get('staticjournallist', function() {
        return redirect('https://cdn.dal.ca/content/dam/dalhousie/pdf/library/Library_Administration/collections/2018_journal_review_list.xlsx');
    })->name('staticjournallist');
    
    Route::get('staticsubjectlist', function() {
        return redirect('https://cdn.dal.ca/content/dam/dalhousie/pdf/library/Library_Administration/collections/subject_list.pdf');
    })->name('staticsubjectlist');
    
    Route::get('about', function() {
        return redirect('https://libraries.dal.ca/about/collection-management/strategy.html');
    })->name('about');

    Route::any('home', 'HomeController@index')->name('home'); 
    
    Route::get('vote/success', 'VoteController@success')->name('vote.success')->middleware('group');
    Route::get('vote', 'VoteController@index')->name('vote')->middleware('group', 'hasvoted');
    
    Route::match(['get','post'],'journals/', 'JournalController@index')->name('journals');
    Route::post('journal', 'JournalController@update')->name('journal.save');
    Route::get('journal/{id}/', 'JournalController@show')->name('journal');
    
    Route::any('journal/{journal_id}/votes/{election_id}', 'VoteController@indexforjournal');
    
    Route::any('journal/{journal_id}/prices/', 'PriceController@indexforjournal')->name('journal.pricelist');
    
    Route::get('edit/journal/{journal_id}/price/{price_id}', 'PriceController@edit')->name('price.edit');
    Route::post('edit/journal/{journal_id}/price/{price_id}', 'PriceController@update')->name('price.save');
    Route::post('create/journal/{journal_id}/price/', 'PriceController@create')->name('price.create');
    Route::any('delete/journal/{journal_id}/price/{price_id}', 'PriceController@delete')->name('price.delete');
    
    Route::get('edit/journal/{journal_id}/platform/{platform_id}', 'PlatformController@edit')->name('journal.platform.edit');
    Route::post('edit/journal/{journal_id}/platform/{platform_id}', 'PlatformController@update')->name('journal.platform.update');
    Route::post('delete/journal/{journal_id}/platform/{platform_id}', 'PlatformController@delete')->name('journal.platform.delete');
    
    Route::get('edit/journal/{id}', 'JournalController@edit')->name('journal.edit');
    Route::post('edit/journal/{id}', 'JournalController@update')->name('journal.update');
    Route::post('edit/journal/{id}/comments/', 'JournalController@comments')->name('journal.update.comments');
    
    Route::get('import/old/', 'ImportController@Index')->name('import');
    Route::post('import/old/', 'ImportController@ImportFromOld')->name('import.old');
    Route::get('export/votes/', 'ExportController@voteExportIndex')->name('export.vote.index');
    Route::any('export/votes/submit', 'ExportController@exportVotes')->name('export.vote.submit');

    Route::any('report', 'ReportController@report')->name('report');
    Route::any('vote/add', 'VoteController@add')->name('vote.add')->middleware('group', 'hasvoted');
    Route::any('vote/remove', 'VoteController@delete')->name('vote.remove')->middleware('group', 'hasvoted');
    Route::any('vote/submit', 'VoteController@store')->name('vote.submit')->middleware('group', 'hasvoted');
}); 

//Resource Routes

Route::get('api/journal/{id}/prices', 'Api\JournalAPIController@prices')->name('GetJournal')->middleware('auth');
Route::get('api/journal/', 'Api\JournalAPIController@index')->name('GetJournals')->middleware('auth');
Route::get('api/library/', 'Api\LibraryAPIController@index')->name('GetLibraries')->middleware('auth');
Route::get('api/platform/{platform_id}/withjournal/{journal_id}', 'Api\PlatformAPIController@showwithjournal')->name('GetPlatformJournal')->middleware('auth');
Route::get('api/platform/', 'Api\PlatformAPIController@index')->name('GetPlatforms')->middleware('auth');
Route::get('api/journal/{id}', 'Api\JournalAPIController@show')->name('GetJournal')->middleware('auth');
Route::get('api/downloads', 'Api\PriceAPIController@downloadGraph')->name('DownloadGraphAPI')->middleware('auth');
Route::get('api/votes', 'Api\VoteAPIController@voteGraph')->name('VoteGraphAPI')->middleware('auth');;
Route::get('api/subject/', 'Api\JournalAPIController@subjects')->name('GetSubjects')->middleware('auth');
Route::get('api/faculty/', 'Api\FacultyAPIController@faculty')->name('GetFaculty')->middleware('auth');
