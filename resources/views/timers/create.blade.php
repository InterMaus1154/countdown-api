<x-app>
    <div class="flex-1 flex flex-col justify-center items-center">
        <form action="{{route('timers.store')}}" method="POST" class="inline-flex flex-col gap-6 bg-white p-6 rounded-md">
            @csrf
            <h2 class="text-center text-secondary font-semibold tracking-widest text-3xl">New Timer</h2>
            @if($errors->has('generic_error'))
                <p class="text-red-500">{{$errors->get('generic_error')[0]}}</p>
            @endif
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <label for="title" class="text-secondary">Title</label>
                    <input type="text" name="title" id="title" placeholder="Title" class="bg-white text-secondary border-zinc-300 border rounded-md p-2" value="{{old('title')}}">
                    @if($errors->has('title'))
                        <p class="text-red-500">{{$errors->get('title')[0]}}</p>
                    @endif
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-1">
                        <label for="description" class="text-secondary">Description</label>
                        <span class="text-disabled text-sm">(Optional)</span>
                    </div>
                    <textarea type="description" name="description" id="description" placeholder="Optional description" class="bg-white text-secondary border-zinc-300 border rounded-md p-2" maxlength="500">{{old('description')}}</textarea>
                    @if($errors->has('description'))
                        <p class="text-red-500">{{$errors->get('description')[0]}}</p>
                    @endif
                </div>
                <div class="flex gap-4 flex-wrap">
                    <div class="flex flex-col gap-2 flex-1">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="bg-white text-secondary border-zinc-300 border rounded-md p-2" value="{{old('end_date')}}">
                        @if($errors->has('end_date'))
                            <p class="text-red-500">{{$errors->get('end_date')[0]}}</p>
                        @endif
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-1">
                            <label for="end_time" class="text-secondary">End Time</label>
                            <span class="text-disabled text-sm">(Optional)</span>
                        </div>
                        <input type="time" id="end_time" name="end_time" class="bg-white text-secondary border-zinc-300 border rounded-md p-2" value="{{old('end_time')}}">
                        @if($errors->has('end_time'))
                            <p class="text-red-500">{{$errors->get('end_time')[0]}}</p>
                        @endif
                    </div>
                </div>
            </div>
            <button type="submit" class="bg-base py-2 px-4 rounded-md text-xl font-semibold tracking-wider cursor-pointer hover:bg-zinc-200">Create Timer</button>
        </form>
    </div>
</x-app>
