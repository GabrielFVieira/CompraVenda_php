<?php

$route = new \CoffeeCode\Router\Router(BASE_URL);

/**
 * APP
 */
$route->namespace("App\Controllers");

/**
 * Public Area
 */
$route->get("/", "DashboardController:index"); // Dashboard page
$route->get("/login", "AccessController:index"); // Login page
$route->post("/login", "AccessController:login"); // Login endpoint

/**
 * Private Area
 */

// $route->get("/logout", "AccessController:logout"); // Logout endpoint

// $route->get("/customers", "Customer:index");
// $route->post("/customers", "Customer:create");
// $route->put("/customers/{id}", "Customer:update");

// $route->get("/sales", "Sales:index");
// $route->post("/sales", "Sales:create");
// $route->put("/sales/{id}", "Sales:update");

// $route->get("/purchases", "Purchase:index");
// $route->post("/purchases", "Purchase:create");
// $route->put("/purchases/{id}", "Purchase:update");
// $route->post("/purchases/{id}/enable/{status}", "Purchase:updateStatus");

// $route->get("/categories", "Category:index");
// $route->post("/categories", "Category:create");
// $route->put("/categories/{id}", "Category:update");

// $route->get("/providers", "Provider:index");
// $route->post("/providers", "Provider:create");
// $route->put("/providers/{id}", "Provider:update");

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