<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(ucfirst($routeName)) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1>hellooooo</h1>

            <div class="mx-auto">
                <form action="
                ">
                    <div class="mb-3">
                        <x-input icon="user" right-icon="pencil" label="Name" placeholder="your name" />
                    </div>
                    <div class="mb-3">
                        <x-input label="Website" placeholder="your-website.com" prefix="https://www." />

                    </div>
                    <div class="mb-3">
                        <x-input label="Website" placeholder="your-website.com" prefix="https://www." />

                    </div>
                    <div class="mb-3">
                        <x-input label="Email" placeholder="your email" suffix="@mail.com" />

                    </div>
                    <div class="mb-3">
                        <x-input label="Name" placeholder="your name">
                            <x-slot name="prepend">
                                <x-button class="h-full" icon="bars-arrow-up" rounded="rounded-l-md" primary />
                            </x-slot>
                        </x-input>

                    </div>
                    <div class="mb-3">
                        <x-input label="Name" placeholder="your name">
                            <x-slot name="append">
                                <x-button class="h-full" icon="bars-arrow-up" rounded="rounded-r-md" primary />
                            </x-slot>
                        </x-input>
                    </div>

                    <div class="mb-3">
                        <x-password label="Secret 🙈" value="I love WireUI ❤️" />
                    </div>

                    <div class="mb-3">
                        <x-number label="How many Burgers?" placeholder="0" />
                    </div>

                    <div class="mb-3">
                        <x-select label="Search a User" placeholder="Select some user" option-label="name"
                            option-value="id" />
                    </div>
                    <div class="mb-3">
                        <x-color-picker label="Select a Color" placeholder="Select the car color" />
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
