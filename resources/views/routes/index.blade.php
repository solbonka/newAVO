@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список маршрутов</h1>

        @if(!empty($routes))
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Название</th>
                </tr>
                </thead>
                <tbody>
                @foreach($routes as $route)
                    <tr>
                        <td>{{ $route->PUBLIC_NAME }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>Маршруты не найдены.</p>
        @endif
    </div>
@endsection
