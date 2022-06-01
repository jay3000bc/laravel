@extends('layouts.app')

@section('content')
<div class="container mx-auto">
                    <div class="min-w-full  rounded lg:grid ">
                        <div class="border-r border-gray-300 lg:col-span-1">
                            <div class="mx-3 my-3">
                                <!-- <div class="relative text-gray-600">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                            class="w-6 h-6 text-gray-300">
                                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </span>
                                    <input type="search"
                                        class="block w-full py-2 pl-10 bg-gray-100 rounded outline-none" name="search"
                                        placeholder="Search" required />
                                </div> -->
                            </div>

                            <ul class="overflow-auto h-[32rem]">
                                <h2 class="my-2 mb-2 ml-2 text-lg text-gray-600">Users</h2>
                                @foreach($user as $user)
                                @if($user->id != Auth::user()->id)
                                <li>
                                    <a href="{{ route('chat',['id'=>$user->id]) }}"
                                        class="flex items-center px-3 py-2 text-sm transition duration-150 ease-in-out border-b border-gray-300 cursor-pointer hover:bg-gray-100 focus:outline-none">
                                        <img class="object-cover w-10 h-10 rounded-full"
                                            src="https://cdn.pixabay.com/photo/2018/09/12/12/14/man-3672010__340.jpg"
                                            alt="username" />
                                        <div class="w-full pb-2">
                                            <div class="flex justify-between">
                                                <span class="block ml-2 font-semibold text-gray-600">{{$user->name}}</span>
                                                <span class="block ml-2 text-sm">
                                                    @if($user->active == 1)
                                                        <span class="text-green-800 font-bold">Online</span>
                                                    @else
                                                        <span class="font-bold">Offline</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <!-- <span class="block ml-2 text-sm text-gray-600">bye</span> -->
                                        </div>
                                    </a>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
@endsection
