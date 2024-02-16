<div>
    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form
            method="post"
            spellcheck = "false"
            action="{{ route('debug.process') }}"
            class="p-6"
        >
            @csrf

            <h2 class="text-lg font-medium">
                Start debugging here!
            </h2>

            <div class="mt-6 space-y-6">
                <x-form.label
                    for="error-log-input"
                    class="sr-only"
                />

                <textarea 
                    placeholder="Type your message..."
                    id="error-log-input"
                    name="message"
                    rows="8"
                    wire:model="debug"
                    class="block w-3/4 py-2 border-gray-400 rounded-md focus:border-gray-400 focus:ring
                    focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-white dark:border-gray-600 dark:bg-dark-eval-1
                    dark:text-gray-300 dark:focus:ring-offset-dark-eval-1"
                >

                </textarea>
                <x-button
                    variant="primary"
                    class=""
                    wire:click.prevent="process"
                    type="submit"
                >
                    {{ __('Enter') }}
                </x-button>
            </div>

            <!-- <div class="mt-6 flex justify-end">
            
            </div> -->
        </form>
        <h2 class="text-lg font-medium font-bold">
            Answer @if(!is_null($hold) && !empty($hold)) - Possible Answer {{count($hold)}} @endif
        </h2>
        <div class ="mt-6 space-y-6 block w-3/4">   
            <p class="font-bold">@if(!is_null($hold) && !empty($hold)) {{($countAns + 1). "."}} @endif</p>
    
            <p class="mt-6" id="current-solution" wire:value="answer">
                {{$answer}}
            </p>
        </div>

        @if(count($hold) > 1)
        <x-button
            variant="primary"
            class="mt-6"
            wire:click.prevent="increment"
            type="submit"
        >
            {{ __('Next Answer') }}
        </x-button>
        @endif
    </div>
</div>
