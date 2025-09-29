@extends('layouts.myapp')
@section('content')
    <div class="mx-auto max-w-screen-xl ">
        <div class="flex md:flex-row flex-col justify-around  items-center px-6 pt-6">
            <div class="md:m-12 p-6 md:w-1/2">
                <img loading="lazy" src="/storage/shop1.png" alt="shop image">
            </div>
            <div class=" relative md:m-12 m-6 md:w-1/2 md:p-6">

                <p>Welcome to our car rental shop conveniently located in the heart of the city. Situated in a prime
                    location, our shop provides easy access and a central hub for all your car rental needs. Whether you are
                    a local resident or a traveler exploring the area, finding us is a breeze.</p>
                <br>
                <p>Our shop is strategically positioned near major transportation hubs, including airports, train stations,
                    and bus terminals, making it incredibly convenient for you to pick up and drop off your rental vehicle.
                    Upon arrival, our friendly staff will warmly greet you, ensuring a smooth and efficient rental process
                    from start to finish.</p>
            </div>

        </div>
        <div class=" p-3 mb-8">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.58705154177!2d112.25749477500213!3d-7.510754492501903!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78150079f23495%3A0xaa24db54146a1005!2sGriya%20Paes%20Salsabila!5e0!3m2!1sid!2sid!4v1758722152486!5m2!1sid!2sid"
                class="w-full h-96" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

    </div>
@endsection
