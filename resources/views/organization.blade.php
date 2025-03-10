@php
// Define an array of colors
$colors = ['bg-[#0081A7]', 'bg-[#00AFB9]', 'bg-[#01B8EC]', 'bg-[#67AEF0]'];
@endphp

@include("layouts.header")
<main class="mt-24 font-dusha">
    <div class="bg-hero-image bg-center bg-cover h-64 bg-no-repeat flex items-center justify-center after:absolute after:left-0 after:right-0 after:bg-after after:w-screen after:h-64 after:opacity-50">
        <div class="text-center relative z-10">
            <span class="text-4xl text-primary">Lembaga di</span> <br>
            <span class="text-4xl text-secondary">Desa</span> <br>
            <span class="text-4xl text-primary">Wringinanom</span>
        </div>
    </div>

    <!-- main section -->
    <section>
        <!-- organization icon -->
        <div class="bg-primary">
            <div class="py-4 flex justify-between mx-auto w-11/12 sm:justify-center sm:gap-36">
                <div id="swiper-logo-lembaga" class="swiper swiper-icon">
                    <div class="swiper-wrapper">
                        @foreach ($organizations as $organization)
                        <div class="swiper-slide flex justify-center">
                            <img
                            class="w-20 rounded-full cursor-pointer scale-75 transition-transform duration-300 icon organization-icon hover:outline hover:outline-secondary hover:outline-4 hover:outline-offset-2 focus:outline focus:outline-secondary focus:outline-4 focus:outline-offset-2 {{ $loop->first ? 'first-item-class' : '' }}"
                            slide-index="{{ $loop->index }}"
                            tabindex="0"
                            src="{{ asset('storage/' . $organization->photo_path) }}"
                            alt="{{ $organization->name }}">
                        </div> 
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- organization icon end -->

        <div id="swiper-lembaga" class="swiper mx-auto w-11/12 my-10 text-white pb-[54px]">
            <div id="swiper-wrapper" class="swiper-wrapper font-sans transition-transform duration-500">

                @foreach ($organizations as $organization)
                <!-- karang taruna description -->
                <div class="swiper-slide {{ $colors[$loop->index % count($colors)] }} rounded-lg h-auto">
                    <div class=" p-12">
                        <!-- title -->
                        <div class="font-bold mb-4 flex flex-col items-center">
                            <span>{{$organization->title}}</span>
                        </div>
                        <!-- title end -->

                        <p class=" text-justify">{{$organization->content}}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination !bottom-0"></div>
        </div>
    </section>
</main>
@include("layouts.footer")