<?php

use App\Services\ClosurePeriod;
use Carbon\Carbon;
use Tests\TestCase;

uses(TestCase::class);

afterEach(fn () => Carbon::setTestNow());

function brussels(string $datetime): Carbon
{
    return Carbon::parse($datetime, 'Europe/Brussels');
}

it('knows when the notice and the closure are active', function (string $at, bool $notice, bool $closed) {
    Carbon::setTestNow(brussels($at));
    $closure = app(ClosurePeriod::class);

    expect($closure->isNoticeActive())->toBe($notice)
        ->and($closure->isClosed())->toBe($closed);
})->with([
    'before the notice' => ['2026-11-30 23:59:59', false, false],
    'early december' => ['2026-12-01 00:00:00', false, false],
    'last day before closure' => ['2026-12-18 23:59:59', false, false],
    'closure starts' => ['2026-12-19 00:00:00', true, true],
    'last day of closure' => ['2027-01-03 23:59:59', true, true],
    'reopening' => ['2027-01-04 00:00:00', false, false],
]);

it('compares dates in Brussels time, not UTC', function () {
    // 18/12 23:30 UTC = 19/12 00:30 en Bruselas
    $at = Carbon::parse('2026-12-18 23:30:00', 'UTC');

    expect(app(ClosurePeriod::class)->isClosed($at))->toBeTrue();
});

it('exposes the reopen date', function () {
    expect(app(ClosurePeriod::class)->reopenDate()->format('d/m/Y'))->toBe('04/01/2027');
});
