Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('manufacturing', ManufacturingController::class);
=======
    Route::resource('invoices', InvoiceController::class);
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('manufacturing', ManufacturingController::class);
    Route::resource('inventories', \App\Http\Controllers\InventoryController::class);
