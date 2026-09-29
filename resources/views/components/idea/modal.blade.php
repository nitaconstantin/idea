@props(['idea' => new App\Models\Idea()])
<x-modal title="{{ $idea->exists ? 'Edit idea' : 'New idea' }}" name="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}">
    <form 

        x-data="{
        status: @js(old('status', $idea->status->value)),
        newLink: '',
        links: @js(old('links', $idea->links)),
        newStep: '',
        steps:@js(old('steps', $idea->steps->map(fn($step) => $step->description)))
      
        }" 
        action="{{ $idea->exists ? route('idea.update', $idea) : route('idea.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        @if($idea->exists)
            @method('PATCH')
        @endif
        <div class="space-y-6">
            <x-form.field
                label="Title"
                name="title"
                placeholder="Enter an idea for your title"
                autofocus
                required
                value="{{ $idea->title }}"
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
                :value="$idea->description"
            />

            <!-- featured image -->
            <div class="space-y-2">
                <label for="image" class="label">Featured Image</label>
                @if($idea->image_path)
                    <div class="space-y-2">
                        <img src="{{ asset('storage/' . $idea->image_path) }}" alt="" class="w-full h-48 object-cover rounded-lg"/>
                    </div>
                    <button class="btn btn-outlined h-10 w-full" form="delete-image-form">Remove Image</button>
                @endif

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
                <button type="submit" class="btn">{{$idea->exists ? 'Update' : 'Create'}}</button>
            </div>
        </div>
        
    </form>

    @if($idea->image_path)
        <form method="POST" action="{{ route('idea.image.destroy', $idea) }}" id="delete-image-form">
            @csrf
            @method('DELETE')
        </form>
    @endif
</x-modal>