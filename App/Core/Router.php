<?php

$route = new \CoffeeCode\Router\Router(BASE_URL);

/**
 * APP
 */
$route->namespace("App\Controllers");

/**
 * Public Area
 */
$route->get("/login", "AccessController:index"); // Login page
$route->post("/login", "AccessController:login"); // Login endpoint

/**
 * Private Area
 */

$route->get("/", "DashboardController:index"); // Dashboard page
$route->get("/logout", "AccessController:logout"); // Logout endpoint

$route->get("/customers", "Customer:index");
$route->post("/customers", "Customer:create");
$route->get("/customers/{id}", "Customer:find");
$route->put("/customers/{id}", "Customer:update");
$route->delete("/customers/{id}", "Customer:remove");

$route->get("/sales", "SaleController:index");
$route->post("/sales", "SaleController:create");
$route->get("/sales/{id}", "SaleController:find");
$route->put("/sales/{id}", "SaleController:update");
$route->delete("/sales/{id}", "SaleController:remove");

$route->get("/products", "ProductController:index");
$route->post("/products", "ProductController:create");
$route->get("/products/{id}", "ProductController:find");
$route->put("/products/{id}", "ProductController:update");
$route->delete("/products/{id}", "ProductController:remove");

$route->get("/purchases", "PurchaseController:index");
$route->post("/purchases", "PurchaseController:create");
$route->get("/purchases/{id}", "PurchaseController:find");
$route->put("/purchases/{id}", "PurchaseController:update");
$route->delete("/purchases/{id}", "PurchaseController:remove");

$route->get("/categories", "CategoryController:index");
$route->post("/categories", "CategoryController:create");
$route->get("/categories/{id}", "CategoryController:find");
$route->put("/categories/{id}", "CategoryController:update");
$route->delete("/categories/{id}", "CategoryController:remove");

$route->get("/providers", "ProviderController:index");
$route->post("/providers", "ProviderController:create");
$route->get("/providers/{id}", "ProviderController:find");
$route->put("/providers/{id}", "ProviderController:update");
$route->delete("/providers/{id}", "ProviderController:remove");

/**
 * ERROR
 */
// $route->group("ops");
// $route->get("/{errcode}", "Web:error");
/**
 * PROCESS
 */
$route->dispatch();

// if ($route->error()) {
//     $route->redirect("/ops/{$route->error()}");
// }