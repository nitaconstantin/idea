<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idea</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground">
    <x-layout.nav/>
    <main class="max-w-7xl mx-auto px-6 pb-10 ">
        {{ $slot }}
    </main>
   
    {{-- <div x-data="{ greeting: 'Hello' }">
        <p x-text="greeting"></p>
        <input type="text" x-model="greeting">
    </div> --}}

    {{-- <div x-data="{show: true}">
        <p x-show="show">You can see me</p>
        <button @click="show = false">Toggle</button>
    </div> --}}


    {{-- <div 
        x-data="{show: true}"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition.opacity.duration.300ms
        class="bg-primary px-4 py-3 absolute bottom-4 right-4 rounded-lg">
        Testing for now...
    </div> --}}

    @session('success')
    <div 
        x-data="{show: true}"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition.opacity.duration.300ms
        class="bg-primary px-4 py-3 absolute bottom-4 right-4 rounded-lg">
        {{ $value }}
    </div>
    @endsession

  
</body>
</html>