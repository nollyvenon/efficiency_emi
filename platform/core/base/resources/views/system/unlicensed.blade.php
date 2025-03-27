<x-core::layouts.base body-class="d-flex flex-column" :body-attributes="['data-bs-theme' => 'dark']">
    <x-slot:title>
        @yield('title')
    </x-slot:title>

    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                @include('core/base::partials.logo')
            </div>

            <x-core::card size="md">
                <x-core::card.body>



                    <div>
                        <form
                            action="{{ route('unlicensed.skip') }}"
                            method="POST"
                        >
                            @csrf

                            @if($redirectUrl)
                                <input type="hidden" name="redirect_url" value="{{ $redirectUrl}}" / >
                            @endif

                            <x-core::button
                                type="submit"
                                class="w-100"
                                color="link"
                                size="sm"
                            >Skip</x-core::button>
                        </form>
                    </div>
                </x-core::card.body>
            </x-core::card>
        </div>
    </div>

    @include('core/base::system.partials.license-activation-modal')
</x-core::layouts.base>
