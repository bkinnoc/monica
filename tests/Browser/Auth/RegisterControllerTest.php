<?php

namespace Tests\Browser\Auth;

use Tests\TestCase;
use GuzzleHttp\Client;
use App\Models\Charity;
use App\Models\User\User;
use Tests\Traits\Asserts;
use Tests\Traits\ApiSignIn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use Laravel\Passport\ClientRepository;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

class RegisterControllerTest extends TestCase
{
    use Asserts;

    public function setUp(): void
    {
        parent::setUp();

        if (app()->environment() != 'testing') {
            $this->markTestSkipped("Set DB_CONNECTION on 'testing' to run this test.");
        }
    }

    protected $jsonStructureOAuthLogin = [
        'access_token',
        'expires_in',
    ];

    private const OAUTH_MULTISTEP_REGISTER_URL = 'http://localhost:8001/api/multi-step-register';

    public function test_multistep_register()
    {
        $charity = Charity::firstOrCreate([
            'name' => 'TestCharity',
            'is_active' => true
        ]);
        $userPassword = 'RandomPassword$01';
        $user = factory(User::class)->make([
            'password' => bcrypt($userPassword),
        ]);

        $response = $this->json('POST', self::OAUTH_MULTISTEP_REGISTER_URL, [
            'email' => $user->email,
            'first_name' => $user->name,
            'last_name' => $user->last_name,
            'dob' => '09/16/1985',
            'password' => $userPassword,
            'password_confirmation' => $userPassword,
            'charity_preference' => $charity->id,
            'policy' => true
        ]);

        $response->assertStatus(200);
        $this->assertTrue(auth()->check());
        $this->assertEquals($user->email, auth()->user()->email);
    }

    private function getActualConnection()
    {
        $handle = fopen('.env', 'r');
        if (!$handle) {
            return;
        }

        $value = null;
        while (($line = fgets($handle)) !== false) {
            if (preg_match('/DB_CONNECTION=(.{1,})/', $line, $matches)) {
                $value = $matches[1];
                break;
            }
        }

        fclose($handle);

        return $value;
    }

    private function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $str .= "\n"; // In case the searched variable is in the last line without \n
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }
            }
        }

        $str = substr($str, 0, -1);

        return file_put_contents($envFile, $str);
    }

    /**
     * @param  string  $path
     * @param  array  $param
     * @return TestResponse
     */
    protected function postClient($path, $param)
    {
        try {
            $http = new Client([
                'timeout' => 30,
            ]);
            $response = $http->post($path, [
                'form_params' => $param,
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->getResponse();
        }

        $factory = new HttpFoundationFactory();
        $response = $factory->createResponse($response);

        return TestResponse::fromBaseResponse($response);
    }
}