<x-layout>
    <div class="">
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>
            <div class="text-muted-foreground text-sm mt-2">
                Capture your thoughts. Make a plan.
            </div>
            <x-card 
                x-data
                @click="$dispatch('open-modal', 'create-idea')"
                is="button" 
                type="button"    
                class="mt-10 cursor-pointer h-32 w-full text-left">
                    <p>What's the idea?</p>
            </x-card>
        </header>
        <div>
            <a href="/ideas" class="btn {{ request()->has('status') ? 'btn-outlined' : '' }}">All</a>
            @foreach (App\IdeaStatus::cases() as $status )
                <a href="/ideas?status={{ $status->value }}" 
                    class="btn {{ request('status') === $status->value ? '' : 'btn-outlined'  }}">
                    {{$status->label()}}
                    <span class="text-xs pl-3">{{$statusCounts->get($status->value)}}</span>
                </a>
            @endforeach
        </div>
        <div class="mt-10 text-muted-foreground">
            <div class="grid md:grid-cols-2 gap-6">        
                @forelse ($ideas as $idea)
                    <x-card href="{{ route('idea.show', $idea) }}">
                        <h3 class="text-foreground text-lg">{{ $idea->title }}</h3>
                        <div class="mt-1">
                           <x-idea.status-label status="{{ $idea->status }}">
                            {{ $idea->status->label() }}
                           
                           </x-idea.status-label>
                            
                        </div>
                        <div class="mt-5 line-clamp-3">
                            {{ $idea->description }}

                        </div>
                        <div class="mt-4">
                            {{ $idea->created_at->diffforHumans() }}
                        </div>
                    </x-card>
                
                @empty
                    <x-card>
                        <p>No ideas at this time.</p>
                    </x-card>
                    
                @endforelse
            </div>
        </div>
        <!-- modal --> 
        <x-modal title="New Idea" name="create-idea">
            <form x-data="{status: 'pending' }" action="{{ route('idea.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <x-form.field
                        label="Title"
                        name="title"
                        placeholder="Enter an idea for your title"
                        autofocus
                        required
                    />
                
                <div class="space-y-2">
                    <label for="status" class="label">Status</label>
                    <div class="flex gap-x-3">
                        @foreach (App\IdeaStatus::cases() as $status)
                            <button 
                                type="button" 
                                @click="status = @js($status->value)"
                                class="btn flex-1 h-10 "
                                {{-- :class="status === @js($status->value) ? '' : 'btn-outlined'" --}}
                                :class="{'btn-outlined' : status !== @js($status->value)}"
                                >
                                    {{ $status->label() }}
                            </button>
                            
                        @endforeach
                        <input type="hidden" name="status" :value="status" class="input">
                    </div>
                    {{-- @error('status')
                        <p class="error">{{ $message }}</p>
                    @enderror --}}
                    <x-form.error name="status"/>
                </div>

                    <x-form.field
                        label="Description"
                        name="description"
                        type="textarea"
                        placeholder="Describe your idea..."
                    />
                    <div class="flex justify-end gap-x-5">
                        <button type="button" @click="$dispatch('close-modal')">Cancel</button>
                        <button type="submit" class="btn">Create</button>
                    </div>
                </div>
                
            </form>
        </x-modal>
    </div>
</x-layout>