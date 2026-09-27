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
                        @if($idea->image_path)
                            <div class="mb-4 -mx-4 -mt-4 rounded-t-lg overflow-hidden">
                                <img src="{{ asset('storage/' . $idea->image_path) }}" alt="" class="w-full h-auto object-cover"/>
                            </div>
                            
                        @endif
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
                            {{ $idea->created_at->diffForHumans() }}
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
            <form 

                x-data="{
                status: 'pending',
                newLink: '',
                links: [],
                newStep: '',
                steps:[]
              
                }" 
                action="{{ route('idea.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >
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

                    <!-- featured image -->
                    <div class="space-y-2">
                        <label for="image" class="label">Featured Image</label>
                        <input type="file" name="image" accept="image/*">
                        <x-form.error name="image"/>
                    </div>

                    <!-- end of featured image -->
                    <!-- steps markup -->
                    <div>
                        
                        <fieldset class="space-y-3">
                            <legend class="label">Actionable Steps</legend>
                            <template x-for="(step, index) in steps" :key="index"> {{-- Folosim index pentru cheie --}}
                                <div class="flex gap-x-2 items-center">
                                    <!-- Corectat: x-model folosește array-ul principal, adăugat și :value -->
                                    <input 
                                        type="text" 
                                        name="steps[]" 
                                        x-model="steps[index]" 
                                        :value="steps[index]" 
                                        class="input"
                                    >
                                    <button 
                                        type="button" 
                                        aria-label="Remove step"
                                        @click="steps.splice(index, 1)"
                                        class="form-muted-icon"
                                    >
                                        <x-icons.close/>
                                    </button>
                                </div>    
                            </template>
                            <div class="flex gap-x-2 items-center">
                                <input 
                                    x-model="newStep"
                                    id="new-step"
                                    placeholder="What needs to be done?"
                                    class="input flex-1"
                                    spellcheck="false"
                                >
                                <button 
                                    type="button" 
                                    @click="steps.push(newStep.trim()); newStep = '';"
                                    :disabled="newStep.trim().length === 0"
                                    aria-label="Add a new step"
                                    class="form-muted-icon"
                                    >
                                        <x-icons.close class="rotate-45"/>
                                </button>
                            </div>
                           {{-- <pre x-text="JSON.stringify(links)"></pre> --}}
                        </fieldset>
                    </div>

                    <!-- end of steps markup -->
                    
                    <div>
                        
                        <fieldset class="space-y-3">
                            <legend class="label">Links</legend>
                            <template x-for="(link, index) in links" :key="index">
                                <div class="flex gap-x-2 items-center">
                                    <!-- Corectat: x-model folosește links[index], adăugat și :value -->
                                    <input 
                                        type="text" 
                                        name="links[]" 
                                        x-model="links[index]" 
                                        :value="links[index]" 
                                        class="input"
                                    >
                                    <button 
                                        type="button" 
                                        aria-label="Remove link"
                                        @click="links.splice(index, 1)"
                                        class="form-muted-icon"
                                    >
                                        <x-icons.close/>
                                    </button>
                                </div>    
                            </template>
                            <div class="flex gap-x-2 items-center">
                                <input 
                                    x-model="newLink"
                                    type="url" 
                                    id="new-link"
                                    placeholder="http://example.com"
                                    autocomplete="url"
                                    class="input flex-1"
                                    spellcheck="false"
                                >
                                <button 
                                    type="button" 
                                    @click="links.push(newLink.trim()); newLink = '';"
                                    :disabled="newLink.trim().length === 0"
                                    aria-label="Add a new link"
                                    class="form-muted-icon"
                                    >
                                        <x-icons.close class="rotate-45"/>
                                </button>
                            </div>
                           {{-- <pre x-text="JSON.stringify(links)"></pre> --}}
                        </fieldset>
                    </div>
                    <div class="flex justify-end gap-x-5">
                        <button type="reset" @click="$dispatch('close-modal')">Cancel</button>
                        <button type="submit" class="btn">Create</button>
                    </div>
                </div>
                
            </form>
        </x-modal>
    </div>
</x-layout>