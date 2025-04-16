<div>
    <div class="container mx-auto max-w-sm md:max-w-7xl">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="">
                    <h2 class="text-2xl font-bold mb-2">Informasi Pegawai</h2>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p><strong>Nama Pegawai : </strong>{{ Auth::user()->name }}</p>
                        <p><strong>Kantor : </strong>{{ $schedule->office->name }}</p>
                        <p><strong>Shift : </strong>{{ $schedule->shift->name }} ({{ $schedule->shift->start_time }} WIB
                            -
                            {{ $schedule->shift->end_time }} WIB)
                        </p>
                        @if ($schedule->is_wfa)
                            <p class="text-green-500"><strong>Status : </strong>WFA</p>
                        @else
                            <p><strong>Status : </strong>WFO</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 gap-6 mt-4">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="text-l font-bold mb-2">Waktu Datang</h4>
                            <p><strong>{{ $attendance ? $attendance->start_time : '-' }}</strong></p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="text-l font-bold mb-2">Waktu Pulang</h4>
                            <p><strong>{{ $attendance ? $attendance->end_time : '-' }}</strong></p>
                        </div>
                    </div>
                </div>
                <div class="">
                    <h2 class="text-2xl font-bold mb-2">Presensi</h2>
                    <div id="map" class="mb-4 rounded-lg border border-gray-300" wire:ignore></div>

                    @if (session()->has('error'))
                        {{-- <div style="color:red;padding:10px;border:1px solid red; background-color: #fdd;"> --}}
                        <div class="text-red-500 p-[10px] border border-red-500 bg-red-100">
                            {{ session('error') }}

                        </div>
                        <button onclick="window.location.href='/admin/attendances'"
                            class="px-4 py-2 mt-3 bg-red-500 hover:bg-red-700 text-white rounded cursor-pointer">Kembali
                            Ke Halaman Awal</button>
                    @endif

                    <form class="row g-3 mt-3" wire:submit="store" enctype="multipart/form-data">
                        <button type="button" onclick="tagLocation()"
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded cursor-pointer">Tag
                            Location</button>
                        @if ($insideRadius)
                            <button type="submit"
                                class="px-4 py-2 bg-green-500 hover:bg-green-700 text-white rounded cursor-pointer">Submit
                                Presensi</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        let map;
        let lat;
        let lng;
        const office = [{{ $schedule->office->latitude }}, {{ $schedule->office->longitude }}]
        const radius = {{ $schedule->office->radius }}
        let component;
        let marker;
        document.addEventListener('livewire:initialized', function() {
            component = @this
            map = L.map('map').setView([{{ $schedule->office->latitude }},
                {{ $schedule->office->longitude }}
            ], 18);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const circle = L.circle(office, {
                color: 'red',
                fillColor: "#f03",
                fillOpacity: .5,
                radius: radius
            }).addTo(map);
        })


        function tagLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    lat = position.coords.latitude;
                    lng = position.coords.longitude;

                    if (marker) {
                        map.removeLayer(marker)
                    }

                    marker = L.marker([lat, lng]).addTo(map)
                    map.setView([lat, lng], 13)

                    if (isWithinRadius(lat, lng, office, radius)) {
                        // alert("Dalam Radius");
                        component.set('insideRadius', true);
                        component.set('latitude', lat)
                        component.set('longitude', lng)
                    }
                })
            }
        }

        function isWithinRadius(lat, lng, center, radius) {
            const is_wfa = {{ $schedule->is_wfa }}
            if (is_wfa) {
                return true
            } else {
                let distance = map.distance([lat, lng], center)
                return distance <= radius;
            }
        }
    </script>
</div>
