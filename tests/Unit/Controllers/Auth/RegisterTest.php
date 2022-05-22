<?php

namespace Tests\Unit\Controllers\Auth;

use Tests\TestCase;
use App\Models\BadWord;
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

            $this->assertEmpty($result->json());
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

            $this->assertArrayHasKey('errors', $result->json());
        }
    }

    /**
     * It returns an array of arrays, each of which contains the data needed to create a user
     *
     * @return array An array of arrays.
     */
    protected function getFailingInfo(): array
    {
        BadWord::create([
            'word' => 'test'
        ]);
        return [
            [
                'email' => 'valid_email@mailinator.com',
                'first_name' => 'Test',
                'last_name' => 'User',
                'dob' => '01/01/2009',
                'password' => 'password',
                'password_confirmation' => 'password'
            ],
            [
                'email' => 'valid_email@mailinator.com',
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
            ]
        ];
    }

    /**
     * It returns an array of arrays, each of which contains the data needed to create a user
     *
     * @return array An array of arrays.
     */
    protected function getPassingInfo(): array
    {
        return [
            [
                'email' => 'valid_email@mailinator.com',
                'first_name' => 'Test',
                'last_name' => 'User',
                'dob' => '01/01/2009',
                'password' => 'finidaD$1',
                'password_confirmation' => 'finidaD$1'
            ]
        ];
    }
}