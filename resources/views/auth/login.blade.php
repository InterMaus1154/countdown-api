<x-app>
    <div class="flex-1 flex flex-col justify-center items-center">
        <form action="{{route('auth.login')}}" method="POST" class="inline-flex flex-col gap-6 bg-white p-6 rounded-md">
            @csrf
            <h2 class="text-center text-secondary font-semibold tracking-widest text-3xl">Login</h2>
            @if($errors->has('invalid'))
                <p class="text-red-500">{{$errors->get('invalid')[0]}}</p>
            @endif
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <label for="username" class="text-secondary">Username</label>
                    <input type="text" name="username" id="username" placeholder="Username" class="bg-white text-secondary border-zinc-300 border rounded-md p-2">
                    @if($errors->has('username'))
                        <p class="text-red-500">{{$errors->get('username')[0]}}</p>
                    @endif
                </div>
                <div class="flex flex-col gap-2">
                    <label for="password" class="text-secondary">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" class="bg-white text-secondary border-zinc-300 border rounded-md p-2">
                    @if($errors->has('password'))
                        <p class="text-red-500">{{$errors->get('password')[0]}}</p>
                    @endif
                </div>
            </div>
            <button type="submit" class="bg-base py-2 px-4 rounded-md text-xl font-semibold tracking-wider cursor-pointer hover:bg-zinc-200">Login</button>
        </form>
    </div>
</x-app>
