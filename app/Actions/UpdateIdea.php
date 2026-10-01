<?php

namespace App\Actions;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateIdea
{
    public function handle(array $attributes, Idea $idea)
    {
        /**
         *  @var User
         */
        // dd($attributes);

         // dd($request->all());
        //  $user ??= Auth::user();

         $data = collect($attributes)->only([
            'title', 'description', 'status', 'links'
         ])->toArray();

         if($attributes['image'] ?? false){
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
         }

        //  $idea = $user->ideas()->create($request->safe()->except(['steps', 'image']));

         // dd($request->steps);

        //  $idea = null;

        DB::transaction(function() use ($idea, $data, $attributes){
            $idea->update($data);

            $idea->steps()->delete();

            $idea->steps()->createMany($attributes['steps'] ?? []);

            // $steps = collect($attributes['steps'] ?? [])->map(fn($step)=>['description' => $step]);
            // $idea->steps()->createMany($steps);

            
        });

        return $idea ? $idea->load('steps') : null;

         
 
        
    }
}