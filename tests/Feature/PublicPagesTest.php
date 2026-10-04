<?php

test('public information pages are accessible to guests', function (string $routeName, string $heading) {
    $this->get(route($routeName))
        ->assertOk()
        ->assertSee($heading);
})->with([
    'about page' => ['about-us', 'درباره ساس‌پت'],
    'support page' => ['support', 'پشتیبانی ساس‌پت'],
    'terms page' => ['terms', 'قوانین و مقررات'],
    'privacy page' => ['privacy', 'حریم خصوصی'],
]);

test('support page provides working email and phone links', function () {
    $this->get(route('support'))
        ->assertSee('href="tel:09133501310"', false)
        ->assertSee('href="mailto:info@sospet.ir"', false);
});

test('public page footer links to every information page', function () {
    $response = $this->get(route('about-us'));

    foreach (['about-us', 'support', 'terms', 'privacy'] as $routeName) {
        $response->assertSee('href="'.route($routeName).'"', false);
    }
});
