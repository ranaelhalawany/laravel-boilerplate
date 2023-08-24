@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Welcome :Name', ['name' => $logged_in_user->name])
        </x-slot>

        <x-slot name="body">
            @lang('Welcome to the Dashboard')

            <div class="mt-4">
               
               <p>Total Users: {{ $totalUsers }}</p>
               <canvas id="userTypeChart" width="400" height="400"></canvas>
               <canvas id="userRegistrationChart" width="400" height="200"></canvas>

           </div>  
          <!-- <div class="mt-4">
 
</div>-->


        </x-slot>

      
    </x-backend.card>
@endsection

@section('head')
 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var userTypesData = @json($userTypes);
        
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('userTypeChart').getContext('2d');
            var userTypeChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: userTypesData.map(item => item.type),
                    datasets: [{
                        data: userTypesData.map(item => item.count),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            // Add more colors here for different types
                        ],
                    }],
                },
            });
        });

    var userRegistrationsData = @json($userRegistrations);
    
    var dates = userRegistrationsData.map(item => item.registration_date);
    var counts = userRegistrationsData.map(item => item.count);
    
    var ctx = document.getElementById('userRegistrationChart').getContext('2d');
    var userRegistrationChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [{
                label: 'Users Registered',
                data: counts,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
            }],
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                    },
                },
                y: {
                    beginAtZero: true,
                    stepSize: 1,
                },
            },
        },
    });
    console.log(userRegistrationsData);
    console.log(userTypesData);
    console.log("rana");

    </script>  
@endsection 

  


