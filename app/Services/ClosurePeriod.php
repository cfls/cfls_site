<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class ClosurePeriod
{
    public function isNoticeActive(?CarbonInterface $at = null): bool
    {
        return $this->now($at)->between($this->noticeFrom(), $this->end());
    }

    public function isClosed(?CarbonInterface $at = null): bool
    {
        return $this->now($at)->between($this->start(), $this->end());
    }

    public function noticeFrom(): Carbon
    {
        return $this->date('notice_from')->startOfDay();
    }

    public function start(): Carbon
    {
        return $this->date('start')->startOfDay();
    }

    public function end(): Carbon
    {
        return $this->date('end')->endOfDay();
    }

    public function reopenDate(): Carbon
    {
        return $this->date('reopen')->startOfDay();
    }

    private function date(string $key): Carbon
    {
        return Carbon::parse(config("closure.$key"), $this->timezone());
    }

    private function now(?CarbonInterface $at): Carbon
    {
        $at ??= $this->fakeNow() ?? Carbon::now();

        return Carbon::instance($at)->copy()->setTimezone($this->timezone());
    }

    private function fakeNow(): ?Carbon
    {
        $fake = config('closure.fake_now');

        return $fake && app()->environment('local') ? Carbon::parse($fake, $this->timezone()) : null;
    }

    private function timezone(): string
    {
        return config('closure.timezone', 'Europe/Brussels');
    }
}
