<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateIdea
{
    public function handle(array $attributes, User $user = null)
    {
        /**
         *  @var User
         */
        // dd($attributes);

         // dd($request->all());
         $user ??= Auth::user();
         $data = collect($attributes)->only([
            'title', 'description', 'status', 'links'
         ])->toArray();

         if($attributes['immage'] ?? false){
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
         }

        //  $idea = $user->ideas()->create($request->safe()->except(['steps', 'image']));

         // dd($request->steps);

        DB::transaction(function() use ($user, $data){
            $idea = $user->ideas()->create($data);

            $steps = collect($attributes['steps'] ?? [])->map(fn($step)=>['description' => $step]);
            $idea->steps()->createMany($steps);
        });

         
 
        
    }
}