<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Live Trip Tracking</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Inter, Arial, sans-serif;
            background: #f4f7fb;
            color: #111827;
        }

        a {
            text-decoration: none;
        }

        .header {
            background: linear-gradient(135deg, #111827, #1f2937);
            color: white;
            padding: 24px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: #ff6b1a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 10px 25px rgba(255, 107, 26, .3);
        }

        .title h1 {
            font-size: 28px;
            margin-bottom: 6px;
        }

        .title p {
            color: #cbd5e1;
            font-size: 14px;
        }

        .status-badge {
            background: rgba(16, 185, 129, .15);
            color: #10b981;
            padding: 10px 18px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            border: 1px solid rgba(16, 185, 129, .3);
        }

        .container {
            margin: 30px auto;
            padding: 0 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 22px;
        }

        .card {
            background: #fff;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .info-box {
            background: #f8fafc;
            border-radius: 18px;
            padding: 18px;
            border: 1px solid #e5e7eb;
        }

        .info-label {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 17px;
            font-weight: 700;
            color: #111827;
        }

        .route-box {
            margin-top: 22px;
            background: linear-gradient(135deg, #eff6ff, #f8fafc);
            padding: 24px;
            border-radius: 20px;
            border: 1px solid #dbeafe;
        }

        .route {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .route-point {
            flex: 1;
        }

        .route-point span {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .route-point strong {
            font-size: 16px;
        }

        .arrow {
            font-size: 26px;
            color: #3b82f6;
        }

        #map {
            width: 100%;
            height: 700px;
            border-radius: 24px;
        }

        .footer-note {
            margin-top: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
        }

        @media(max-width: 992px) {

            .grid {
                grid-template-columns: 1fr;
            }

            #map {
                height: 500px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .header {
                padding: 20px;
            }
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 22px;
            padding: 20px;
            border-radius: 22px;
            background: linear-gradient(135deg, #f8fafc, #eef2ff);
            border: 1px solid #e5e7eb;
        }

        .profile-image {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
            background: #fff;
        }

        .profile-content h3 {
            font-size: 22px;
            margin-bottom: 6px;
        }

        .profile-content p {
            color: #6b7280;
            font-size: 14px;
        }

        .live-pulse {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #10b981;
            display: inline-block;
            margin-right: 6px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.5);
                opacity: .5;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>

</head>

<body>

    {{-- HEADER --}}
    <div class="header">

        <div class="logo-area">

            <div class="logo">
                🚚
            </div>

            <div class="title">
                <h1>Flecso Live Tracking</h1>
                <p>
                    Real-time shipment & driver tracking system
                </p>
            </div>

        </div>

        <div class="status-badge">
            🟢 {{ ucfirst($trip->trip_status) }}<br>
             {{ Carbon\Carbon::parse($trip->schedule_datetime)->format('d M Y h:i A') ?? 'N/A' }}
        </div>

    </div>

    <div class="container">

        <div class="grid">

            {{-- LEFT SIDE --}}
            <div>

                {{-- TRIP DETAILS --}}
                <div class="card">

                    <div class="card-title">
                        📦 Trip Information
                    </div>

                    <div class="info-grid">

                        <div class="info-box">
                            <div class="info-label">
                                Trip ID
                            </div>

                            <div class="info-value">
                                {{ $trip->trip_id }}
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="info-label">
                                Trip Type
                            </div>

                            <div class="info-value">
                                {{ ucfirst($trip->trip_type) }}
                            </div>
                        </div>

                    </div>

                    {{-- ROUTE --}}
                    <div class="route-box">

                        <div class="card-title" style="margin-bottom:18px;">
                            📍 Route Details
                        </div>

                        <div class="route">

                            <div class="route-point">
                                <span>Pickup Location</span>
                                <strong>
                                    {{ $trip->pickup_location }}
                                </strong>
                            </div>

                            <div class="arrow">
                                ➜
                            </div>

                            <div class="route-point" style="text-align:right">
                                <span>Delivery Location</span>
                                <strong>
                                    {{ $trip->delivery_location }}
                                </strong>
                            </div>

                        </div>

                    </div>

                </div>

                {{-- DRIVER DETAILS --}}
                <div class="card" style="margin-top:22px;">

                    <div class="card-title">
                        👨‍✈️ Driver Details
                    </div>

                    <div class="profile-card">

                        <img src="{{ $trip->driver->driver_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($trip->driver->full_name ?? 'Driver') . '&background=FF6B1A&color=fff&size=200' }}"
                            class="profile-image">

                        <div class="profile-content">

                            <h3>
                                {{ $trip->driver->full_name ?? 'N/A' }}
                            </h3>

                            <p>
                                <span class="live-pulse"></span>
                                Live Driver Tracking Enabled
                            </p>

                            <p style="margin-top:8px;">
                                📞 {{ $trip->driver->phone ?? 'N/A' }}
                            </p>

                             <p style="margin-top:8px;">
                                📧 {{ $trip->driver->email ?? 'N/A' }}
                             </p>

                        </div>

                    </div>

                   

                </div>

                {{-- VEHICLE DETAILS --}}
                <div class="card" style="margin-top:22px;">

                    <div class="card-title">
                        🚛 Truck & Container
                    </div>

                    <div class="profile-card">

                        <img src="{{ $trip->truck->image ?? 'https://cdn-icons-png.flaticon.com/512/744/744465.png' }}"
                            class="profile-image">

                        <div class="profile-content">

                            <h3>
                                {{ $trip->truck->truck_number ?? 'N/A' }}
                            </h3>

                            <p>
                                🚚 Active Shipment Vehicle
                            </p>

                            <p style="margin-top:8px;">
                                📦 Container:
                                {{ $trip->container->container_license_number ?? 'N/A' }}
                            </p>

                        </div>

                    </div>

                    <div class="info-grid">

                        <div class="info-box">
                            <div class="info-label">
                                Truck License Plate Number
                            </div>

                            <div class="info-value">
                                {{ $trip->truck->license_plate_number ?? 'N/A' }}
                            </div>
                        </div>


                        <div class="info-box">
                            <div class="info-label">
                                Container Serial Number 📦
                            </div>

                            <div class="info-value">
                                {{ $trip->container->serial_number ?? 'N/A' }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            {{-- RIGHT SIDE --}}
            <div>

                <div class="card">

                    <div class="card-title">
                        🛰️ Live Driver Location
                    </div>

                    <div id="map"></div>

                    <div class="footer-note">
                        Live GPS tracking updates automatically in real-time.
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- PUSHER --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    {{-- ECHO --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>

    <script>

        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env("PUSHER_APP_KEY") }}',
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            forceTLS: true
        });

        let map;
        let marker;

        window.initMap = function () {

            const lat = {{ $driverLocation->latitude ?? 31.5204 }};
            const lng = {{ $driverLocation->longitude ?? 74.3587 }};

            const currentPosition = {
                lat: parseFloat(lat),
                lng: parseFloat(lng)
            };

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: currentPosition,
                styles: [{
                    featureType: "poi",
                    stylers: [{
                        visibility: "off"
                    }]
                },
                {
                    featureType: "transit",
                    stylers: [{
                        visibility: "off"
                    }]
                }
                ]
            });

            marker = new google.maps.Marker({
                position: currentPosition,
                map,
                title: 'Driver Live Location',
                animation: google.maps.Animation.DROP,
                icon: {
                    url: "https://maps.google.com/mapfiles/ms/icons/truck.png"
                }
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding:10px;min-width:180px">
                        <strong style="font-size:15px">
                            🚚 {{ $trip->driver->full_name ?? 'Driver' }}
                        </strong>
                        <br><br>

                        📍 Live tracking enabled
                        <br>

                        🚛 Truck:
                        {{ $trip->truck->truck_number ?? 'N/A' }}
                    </div>
                `
            });

            marker.addListener("click", () => {
                infoWindow.open(map, marker);
            });

            Echo.private('admin.{{ $trip->admin_id }}')
                .listen('.driver.location.updated', (e) => {

                    if (e.location.driver_id != {{ $trip->driver_id }}) {
                        return;
                    }

                    const position = {
                        lat: parseFloat(e.location.latitude),
                        lng: parseFloat(e.location.longitude)
                    };

                    marker.setPosition(position);

                    map.panTo(position);
                });
        };

    </script>

    {{-- GOOGLE MAP --}}
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQqP59sFi_cXyk8Afq_AY4Dkg4DCf-xj0&callback=initMap">
        </script>

</body>

</html>