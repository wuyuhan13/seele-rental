@extends('layouts.member')

@section('member')

    <table class="table table-hover">
        <thead>
        <th>ID</th>
        <th>Product</th>
        <th>Rental Money</th>
        <th>Deposit Money</th>
        <th>Status</th>
        <th>UpdatedAt</th>
        <th>Options</th>
        </thead>
        @forelse($rentals as $rental)
            <tr>
                <td>{{$rental->id}}</td>
                <td>{{$rental->product->title}}</td>
                <td>{{$rental->charge}}</td>
                <td>{{$rental->deposit}}</td>
                <td>{{$rental->statusText()}}</td>
                <td>{{$rental->updated_at}}</td>
                <td>
                @if($rental->status == \App\Models\Rental::STATUS_A_APPLY)
                    <span>Wait B Confirm</span>
                    @elseif($rental->status == \App\Models\Rental::STATUS_B_CONFIRM)
                    <a href="{{route('rentals.a_confirm', $rental)}}">You Should be Confirm</a>
                    @elseif($rental->status == \App\Models\Rental::STATUS_A_CONFIRM)
                        <a href="{{route('rentals.a_confirm', $rental)}}">Complete Apply</a>
                    @elseif($rental->status == \App\Models\Rental::STATUS_A_COMPLETE)
                        <span>WAIT B CONFIRM</span>
                    @elseif($rental->status == \App\Models\Rental::STATUS_COMPLETE)
                    <span>Complete</span>
                    @elseif($rental->status == \App\Models\Rental::STATUS_REJECT)
                    <span>Reject.Reason:{{$rental->reject_reason}}</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">
                    None.
                </td>
            </tr>
        @endforelse
    </table>

    <div class="text-right">
        {{$rentals->render()}}
    </div>

@endsection