<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::redirect('/sitemap.xml', Storage::url('sitemap.xml'), 301);
