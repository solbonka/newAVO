@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Мои заказы</h1>

        @if ($orders->isEmpty())
            <p>У вас пока нет заказов.</p>
        @else
            <div class="col">
                @foreach ($orders as $order)
                    <div class="col-md-10 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Заказ №{{ $order->id }}</h5>
                                <p class="card-text"><strong>Статус:</strong> {{ $order->getTranslatedStatus() }}</p>
                                <p class="card-text"><strong>Дата создания:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">Просмотреть</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection


