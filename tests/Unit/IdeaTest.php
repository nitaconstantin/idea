<?php

namespace Tests\Unit;

use App\Models\Idea;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;

// 💡 Această linie obligă testul să folosească structura de Feature (încarcă Laravel + curăță DB)
uses(Tests\TestCase::class, RefreshDatabase::class);
test('it belongs to a user', function(){
    $idea = Idea::factory()->create();
    expect($idea->user()->toBeInstanceOf(User::class));
});
