<?php

namespace Tests\Unit\Controllers\Auth;

use Tests\TestCase;
use App\Models\BadWord;
use App\Models\Charity;
use App\Models\User\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_succeeds_in_signing_up()
    {
        NotificationFacade::fake();
        $users = $this->getPassingInfo();
        foreach ($users as $user) {

            $result = $this->postJson('/register', array_merge($user, [
                'lang' => 'en',
                'policy' => true
            ]));

            $this->assertNotEquals(422, $result->status());
        }
    }

    /** @test */
    public function it_fails_in_signing_up()
    {
        NotificationFacade::fake();
        $users = $this->getFailingInfo();
        foreach ($users as $user) {

            $result = $this->postJson('/register', array_merge($user, [
                'lang' => 'en',
                'policy' => true
            ]));

            $this->assertEquals(422, $result->status());
        }
    }

    /**
     * It returns an array of arrays, each of which contains the data needed to create a user
     *
     * @return array An array of arrays.
     */
    protected function getFailingInfo(): array
    {
        Charity::factory()->create();
        BadWord::create([
            'word' => 'test'
        ]);
        return [
            [
                'email' => 'valid_email2@mailinator.com',
                'first_name' => 'Test',
                'last_name' => 'User',
                'dob' => '01/01/2009',
                'password' => 'password',
                'password_confirmation' => 'password'
            ],
            [
                'email' => 'valid_email3@mailinator.com',
                'first_name' => 'Test',
                'last_name' => 'User',
                'dob' => '01/01/2015',
                'password' => 'finidaD$1',
                'password_confirmation' => 'finidaD$1'
            ],
            [
                'email' => 'test@mailinator.com',
                'first_name' => 'Test',
                'last_name' => 'User',
                'dob' => '01/01/2009',
                'password' => 'finidaD$1',
                'password_confirmation' => 'finidaD$1'
            ],
            // [
            //     'email' => 'valid_email4@mailinator.com',
            //     'first_name' => 'Test',
            //     'last_name' => 'User',
            //     'dob' => '01/01/2009',
            //     'password' => 'finidaD$1',
            //     'password_confirmation' => 'finidaD$1',
            // ],
        ];
    }

    /**
     * It returns an array of arrays, each of which contains the data needed to create a user
     *
     * @return array An array of arrays.
     */
    protected function getPassingInfo(): array
    {
        Charity::factory()->create();
        return [
            [
                'email' => 'valid_email0@mailinator.com',
                'first_name' => 'Test',
                'last_name' => 'User',
                'dob' => '01/01/2009',
                'password' => 'finidaD$1',
                'password_confirmation' => 'finidaD$1',
            ],
            [
                'email' => 'valid_email1@mailinator.com',
                'first_name' => 'Test',
                'last_name' => 'User',
                'dob' => '01/01/2009',
                'password' => 'finidaD$1',
                'password_confirmation' => 'finidaD$1',
                'charity_preference' => Charity::first()->id
            ]
        ];
    }
}