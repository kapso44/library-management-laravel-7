@extends('layout')

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="card uper">
  <div class="card-header">
    Check-in Book
  </div>
  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
      <form method="post" action="{{ route('loan.update') }}">
          <div class="form-group">
              @csrf
              <label for="country_name">Book Id :</label>
              <input type="text" value="{{$book_id}}" class="form-control" name="book_id"/>
          </div>
          <div class="form-group">
              <label for="symptoms">Branch Id :</label>
              <input type="text" value="{{$branch_id}}" class="form-control" name="branch_id"/>
          </div>
          <div class="form-group">
              <label for="cases">Card No :</label>
              <input type="text" value="{{$card_no}}" class="form-control" name="card_no"/>
          </div>
          <button type="submit" class="btn btn-primary">Check In</button>
      </form>
  </div>
</div>
@endsection