<?php

$route = new \CoffeeCode\Router\Router(URL_BASE);
/**
 * APP
 */
$route->namespace("App\Controllers");
/**
 * Public Area
 */
$route->get("/", "Home:index");
$route->get("/home", "Home:index");
$route->get("/login", "AcessoRestrito:captcha");
$route->post("/login", "AcessoRestrito:login");
/**
 * Private Area
 */
$route->get("/Dashboard", "Dashboard:index");
$route->get("/logout", "AcessoRestrito:logout");

$route->get("/customers", "Customer:index");
$route->post("/customers", "Customer:create");
$route->put("/customers/{id}", "Customer:update");

$route->get("/sales", "Sales:index");
$route->post("/sales", "Sales:create");
$route->put("/sales/{id}", "Sales:update");

$route->get("/purchases", "Purchase:index");
$route->post("/purchases", "Purchase:create");
$route->put("/purchases/{id}", "Purchase:update");
$route->post("/purchases/{id}/enable/{status}", "Purchase:updateStatus");

$route->get("/categories", "Category:index");
$route->post("/categories", "Category:create");
$route->put("/categories/{id}", "Category:update");

$route->get("/providers", "Provider:index");
$route->post("/providers", "Provider:create");
$route->put("/providers/{id}", "Provider:update");

/**
 * ERROR
 */
$route->group("ops");
$route->get("/{errcode}", "Web:error");
/**
 * PROCESS
 */
$route->dispatch();

if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}