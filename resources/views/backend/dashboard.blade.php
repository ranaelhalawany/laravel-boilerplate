@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@2.0.0/dist/chartjs-adapter-moment.min.js"></script>

    <x-backend.card>
        <x-slot name="header">
            @lang('Welcome :Name', ['name' => $logged_in_user->name])
        </x-slot>

        <x-slot name="body">
            @lang('Welcome to the Dashboard')

            <div class="mt-4">
                <p>Total Users: {{ $totalUsers }}</p>
            </div>
            <div class="mt-4">
                <canvas id="userTypeChart" width="200" height="200"></canvas>
            </div>
            <div class="mt-4">
             <canvas id="userRegistrationChart" width="400" height="200"></canvas>
            </div>
        </x-slot>
    </x-backend.card>
    <script>

        var userTypesData = @json($userTypes);
        var userRegistrationsData = @json($userRegistrations);
        console.log(@json($userRegistrations));

        var userTypeCtx = document.getElementById('userTypeChart').getContext('2d');
        var userTypeLabels = userTypesData.map(item => item.type);
        var userTypeData = userTypesData.map(item => item.count);
        var userTypeBackgroundColors = ['red', 'green', 'blue', 'orange', 'purple', 'yellow', 'cyan'];

        var userTypeConfig = {
            type: 'pie',
            data: {
                labels: userTypeLabels,
                datasets: [{
                    data: userTypeData,
                    backgroundColor: userTypeBackgroundColors
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        };
        
        new Chart(userTypeCtx, userTypeConfig);

        var userRegistrationCtx = document.getElementById('userRegistrationChart').getContext('2d');
        var userRegistrationLabels = userRegistrationsData.map(item => item.registration_date);
        var userRegistrationData = userRegistrationsData.map(item => item.count);

        var userRegistrationConfig = {
    type: 'bar',
    data: {
        labels: userRegistrationLabels,
        datasets: [{
            label: 'Users Registered',
            data: userRegistrationData,
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
        }]
    },
    options: {
        responsive: true,
      maintainAspectRatio: false,

    },
};


        new Chart(userRegistrationCtx, userRegistrationConfig);
     
      

    </script>
@endsection

