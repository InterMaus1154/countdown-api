<x-app>
    <hgroup class="flex items-center gap-4">
        <h2 class="text-4xl font-semibold text-secondary">Timer List</h2>
        <a href="{{route('timers.create')}}"
           class="bg-white py-1 px-2 rounded-md text-lg text-headline border border-zinc-200 font-semibold tracking-wider hover:bg-base cursor-pointer">Add
            +</a>
    </hgroup>
    @if($errors->any())
        <div class="my-4">
            @foreach($errors->all() as $error)
                <p class="text-red-600">{{$error}}</p>
            @endforeach
        </div>
    @endif
    @if(session()->has('success'))
        <div class="my-4">
            <p class="text-green-600">{{session('success')}}</p>
        </div>
    @endif
    <div class="my-4">
        <table class="w-full text-left">
            <thead class="bg-zinc-50 border-b border-b-zinc-200">
            <tr>
                <th class="text-secondary py-2 px-4 font-semibold">Id</th>
                <th class="text-secondary py-2 px-4 font-semibold">Title</th>
                <th class="text-secondary py-2 px-4 font-semibold">Description</th>
                <th class="text-secondary py-2 px-4 font-semibold">Ends At</th>
                <th class="text-secondary py-2 px-4 font-semibold">Added By</th>
                <th class="text-secondary py-2 px-4 font-semibold">Remaining</th>
                <th class="text-secondary py-2 px-4 font-semibold">Actions</th>
            </tr>
            </thead>
            <tbody class="dividy-y divide-zinc-200">
            @foreach($timers as $timer)
                <tr class="hover:bg-zinc-50">
                    <td class="text-secondary py-2 px-4 font-semibold">{{$timer->getId()}}</td>
                    <td class="text-secondary py-2 px-4 font-semibold">{{$timer->getTitle()}}</td>
                    <td class="text-secondary py-2 px-4 font-semibold">{{\Illuminate\Support\Str::limit($timer->getDescription(), 50, preserveWords: true)}}</td>
                    <td class="text-secondary py-2 px-4 font-semibold">{{$timer->getFormattedEndsAt()}}</td>
                    <td class="text-secondary py-2 px-4 font-semibold">{{$timer->user->getUsername()}}</td>
                    <td class="text-secondary py-2 px-4 font-semibold">
                        @if($timer->isInPast())
                            <span class="text-red-500 uppercase">expired</span>
                        @else
                            {{$timer->getDaysRemaining()}} days
                        @endif
                    </td>
                    <td class="text-secondary py-2 px-4 font-semibold">
                        <div class="flex gap-2">
                            @if(! $timer->isInPast())
                                <a href="{{route('timers.edit', ['id' => $timer->getId()])}}"
                                   class="bg-white py-1 px-2 rounded-md text-lg text-headline border border-zinc-200 font-semibold tracking-wider hover:bg-base cursor-pointer">Edit</a>
                            @endif
                            <form method="POST" action="{{route('timers.delete', ['id' => $timer->getId()])}}"
                                  onsubmit="return window.confirm('Delete timer?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 py-1 px-2 rounded-md text-lg text-base cursor-pointer">Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-app>
