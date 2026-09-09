<?php

namespace Tests\Unit;

use App\Support\Presence;
use Carbon\Carbon;
use Tests\TestCase;

class PresenceTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_forced_online_and_offline_modes(): void
    {
        config(['portfolio.presence.mode' => 'online']);
        $this->assertTrue(Presence::online());
        $this->assertSame('Online', Presence::label());

        config(['portfolio.presence.mode' => 'offline']);
        $this->assertFalse(Presence::online());
        $this->assertSame('Offline', Presence::label());
    }

    public function test_schedule_is_online_during_malaysia_hours(): void
    {
        config([
            'portfolio.presence.mode' => 'schedule',
            'portfolio.presence.timezone' => 'Asia/Kuala_Lumpur',
            'portfolio.presence.online_from' => '10:00',
            'portfolio.presence.online_until' => '22:00',
        ]);

        Carbon::setTestNow(Carbon::parse('2026-08-27 10:00:00', 'Asia/Kuala_Lumpur'));
        $this->assertTrue(Presence::online());

        Carbon::setTestNow(Carbon::parse('2026-08-27 21:59:00', 'Asia/Kuala_Lumpur'));
        $this->assertTrue(Presence::online());

        Carbon::setTestNow(Carbon::parse('2026-08-27 22:00:00', 'Asia/Kuala_Lumpur'));
        $this->assertFalse(Presence::online());

        Carbon::setTestNow(Carbon::parse('2026-08-27 03:15:00', 'Asia/Kuala_Lumpur'));
        $this->assertFalse(Presence::online());
    }
}
