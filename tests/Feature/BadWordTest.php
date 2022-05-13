<?php

namespace Tests\Feature;

use App\Models\BadWord;
use App\Models\User\User;
use Tests\FeatureTestCase;
use App\Models\Contact\Contact;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BadWordTest extends FeatureTestCase
{
    use DatabaseTransactions, WithFaker;

    public function test_can_set_goodword_successfully()
    {
        // BadWord::truncate();
        foreach ($this->unsuccessfulPatterns() as $pattern => $options) {
            BadWord::create([
                'word' => $pattern,
            ]);
            $validator = Validator::make([
                'word' => $options[0],
            ], [
                'word' => new \App\Rules\BadWord,
            ]);

            // dump([$options[0], $pattern, $validator->errors()->toArray()['word'][0], $options[1], $validator->fails(), BadWord::pluck('word')]);

            $this->assertEquals($validator->fails(), $options[1]);
            // BadWord::truncate();
        }
    }

    public function test_can_set_badword_unsuccessfully()
    {
        // BadWord::truncate();
        foreach ($this->patterns() as $pattern => $options) {
            BadWord::create([
                'word' => $pattern,
            ]);
            $validator = Validator::make([
                'word' => $options[0],
            ], [
                'word' => new \App\Rules\BadWord,
            ]);

            // dump([$options[0], $pattern, $validator->errors()->toArray()['word'][0], $options[1], $validator->fails(), BadWord::pluck('word')]);

            $this->assertEquals($validator->fails(), $options[1]);
            // BadWord::truncate();
        }
    }

    protected function patterns()
    {
        return [
            'admin@' => ['admin789@email.com', false],
            'admin$' => ['admin101@email.com', false],
        ];
    }

    protected function unsuccessfulPatterns()
    {
        return [
            'admin' => ['admin@email.com', true],
            '^admin' => ['admin123@email.com', true],
            'admin*' => ['admin345@email.com', true]
        ];
    }
}