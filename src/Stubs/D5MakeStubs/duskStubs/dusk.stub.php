<?php

namespace Tests\Browser\DummyBase;

use App\Lib\Common\Dictionary\Dwfaker;
use App\Model\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DummyMethodTest extends DummyBaseBase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function testExample()
    {

        $this->browse(function (Browser $browser) {

            $faker = new Dwfaker();
            $url = "";
            $user = User::all()->first();
            $browser
                ->visit($url)
                ->loginAs($user,"admin")
                ->pause(1000)
                ->assertSee('Laravel');
        });
    }
}
