<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::get('/reports', fn () => Blade::render(
        '<x-ob::sidebar.item href="/reports" icon="inbox" label="Reports" /><x-ob::sidebar.item href="/tasks" icon="circle-check" label="Tasks" />'
    ));
    Route::get('/reports/{id}', fn () => Blade::render(
        '<x-ob::sidebar.item href="/reports" icon="inbox" label="Reports" /><x-ob::dock.item href="/tasks" icon="circle-check" label="Tasks" />'
    ));
    Route::get('/dock', fn () => Blade::render(
        '<x-ob::dock.item href="/dock" icon="inbox" label="Home" /><x-ob::dock.item href="/tasks" icon="circle-check" label="Tasks" />'
    ));
});

it('marks only the current sidebar item as the current page', function () {
    $content = $this->get('/reports')->assertOk()->getContent();

    expect(substr_count($content, 'aria-current="page"'))->toBe(1)
        ->and(strpos($content, 'aria-current="page"'))->toBeLessThan(strpos($content, 'aria-label="Tasks"'))
        ->and($content)->toContain('wire:navigate')
        ->and($content)->toContain('aria-label="Tasks"');
});

it('treats nested paths as active', function () {
    $content = $this->get('/reports/42')->getContent();

    expect(substr_count($content, 'aria-current="page"'))->toBe(1);
});

it('applies the same active rule to dock items and renders their label only as aria-label', function () {
    $content = $this->get('/dock')->getContent();

    expect(substr_count($content, 'aria-current="page"'))->toBe(1)
        ->and(strpos($content, 'aria-current="page"'))->toBeLessThan(strpos($content, 'aria-label="Tasks"'))
        ->and($content)->not->toContain('>Home<')
        ->and($content)->not->toContain('>Tasks<');
});

it('renders a sidebar item label as a tooltip text once and a hidden-when-collapsed span', function () {
    $content = $this->get('/reports')->getContent();

    expect(substr_count($content, '>Reports<'))->toBe(1)
        ->and($content)->toContain('x-show="open && (collapsed)"');
});

it('renders the shell with a persisted collapse state and overlay-safe main', function () {
    $this->blade('<x-ob::app-shell>Body<x-slot:sidebar>S</x-slot:sidebar><x-slot:dock>D</x-slot:dock></x-ob::app-shell>')
        ->assertSee("\$persist(false).as('ob-sidebar-collapsed')", false)
        ->assertSee('lg:grid-cols-[auto_1fr]', false)
        ->assertSee('pb-[calc(6rem+env(safe-area-inset-bottom))] lg:pb-0', false)
        ->assertSee('Body');
});

it('renders a collapsible sidebar with labelled toggles', function () {
    $this->blade('<x-ob::sidebar>Nav<x-slot:brand>Brand</x-slot:brand><x-slot:footer>Foot</x-slot:footer></x-ob::sidebar>')
        ->assertSee('aria-label="Collapse sidebar"', false)
        ->assertSee('aria-label="Expand sidebar"', false)
        ->assertSee('w-16!', false)
        ->assertSee('Brand')
        ->assertSee('Foot');
});

it('renders the dock with scroll-driven hiding and a forwarding action', function () {
    $this->blade('<x-ob::dock><x-ob::dock.action icon="plus" label="New" wire:click="create" /></x-ob::dock>')
        ->assertSee('x-data="obDock"', false)
        ->assertSee('translate-y-[150%]', false)
        ->assertSee('scroll.window.passive', false)
        ->assertSee('aria-label="New"', false)
        ->assertSee('wire:click="create"', false);
});

it('renders a page header with an h1 and a compact bar triggered by intersection', function () {
    $this->blade('<x-ob::page-header title="Reports" description="All of them"><x-slot:actions>Act</x-slot:actions></x-ob::page-header>')
        ->assertSee('<h1', false)
        ->assertSee('>Reports</h1>', false)
        ->assertSee('x-intersect:leave', false)
        ->assertSee('All of them')
        ->assertSee('Act');
});

it('only compacts the page header once its title has scrolled out above the viewport', function () {
    $this->blade('<x-ob::page-header title="Reports" />')
        ->assertSee('x-intersect:leave="compact = $el.getBoundingClientRect().top < 0"', false);
});

it('pressed stat exposes aria-pressed and forwards attributes', function () {
    $this->blade('<x-ob::stat label="Open" value="12" selected wire:click="filter" />')
        ->assertSee('aria-pressed="true"', false)
        ->assertSee('border-accent bg-accent-soft', false)
        ->assertSee('wire:click="filter"', false)
        ->assertSee('tabular-nums', false);

    $this->blade('<x-ob::stat label="Open" value="12" />')->assertSee('aria-pressed="false"', false);
});

it('renders card, section and empty-state', function () {
    $this->blade('<x-ob::card :padding="false">C</x-ob::card>')->assertSee('bg-surface-1')->assertDontSee('p-5');
    $this->blade('<x-ob::section heading="Recent" description="Latest">Body<x-slot:actions>A</x-slot:actions></x-ob::section>')
        ->assertSee('Recent')->assertSee('Latest')->assertSee('Body');
    $this->blade('<x-ob::empty-state icon="inbox" heading="Nothing here">Add one.<x-slot:action>Go</x-slot:action></x-ob::empty-state>')
        ->assertSee('Nothing here')->assertSee('Add one.')->assertSee('Go');
});

it('puts a back link above the page title when given one', function () {
    $this->blade('<x-ob::page-header title="Report 12" back="/reports" back-label="Reports" />')
        ->assertSeeInOrder(['href="/reports"', 'Reports', 'Report 12'], false)
        ->assertSee('wire:navigate', false);
});

it('renders no back link on a top-level page', function () {
    $this->blade('<x-ob::page-header title="Reports" />')->assertDontSee('<a', false);
});

it('renders a standalone back link that keeps caller attributes', function () {
    $this->blade('<x-ob::back-link href="/tasks" x-on:click="prepare()">Tasks</x-ob::back-link>')
        ->assertSee('href="/tasks"', false)
        ->assertSee('x-on:click="prepare()"', false)
        ->assertSee('Tasks');
});

it('renders an optional status area between the sidebar nav and footer', function () {
    $this->blade('<x-ob::sidebar>Nav<x-slot:status>v1.2</x-slot:status><x-slot:footer>Foot</x-slot:footer></x-ob::sidebar>')
        ->assertSeeInOrder(['Nav', 'v1.2', 'Foot']);
});

it('marks content blocks for the entrance animation', function (string $blade) {
    $this->blade($blade)->assertSee('data-ob-reveal', false);
})->with([
    'card' => '<x-ob::card>Body</x-ob::card>',
    'stat' => '<x-ob::stat label="Open" value="3" />',
    'section' => '<x-ob::section heading="Team">Body</x-ob::section>',
    'empty state' => '<x-ob::empty-state icon="inbox" heading="Nothing yet" />',
    'table' => '<x-ob::table>Rows</x-ob::table>',
    'page header' => '<x-ob::page-header title="Reports" />',
]);

it('only fades the main content area so fixed overlays inside it stay anchored to the viewport', function () {
    $html = (string) $this->blade('<x-ob::app-shell>Body</x-ob::app-shell>');

    expect($html)->toContain('<main data-ob-fade')->not->toContain('<main data-ob-reveal');
});
