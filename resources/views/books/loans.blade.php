@extends('layout')

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="uper">
    @if(session()->get('success'))
        <div class="alert alert-success">
        {{ session()->get('success') }}  
        </div><br />
    @endif
  <!-- Search form -->
  <form method="post" action="{{ route('books.loans') }}">
    <div class="form-group">
      @csrf
      <div class="input-group">
        <div class="md-form mt-0">
          <input class="form-control" type="text" name="query" placeholder="" aria-label="Search">
        </div>
        <div class="input-group-append">
          <button type="submit" class="btn btn-primary" >Search</button>
        </div>
      </div>
    </div>
  </form>
  <table class="table table-striped">
    <thead>
        <tr>
          <td>Book ID</td>
          <td>Card No</td>
          <td>Borrower Name</td>
          <td>Branch Id</td>
          <td>Check-out</td>
          <td>Due Date</td>
          <td>Action</td>
        </tr>
    </thead>
    @if(isset($loans))
    <tbody>
        @foreach($loans as $loan)
        <tr>
            <td>{{$loan->book_id}}</td>
            <td>{{$loan->card_no}}</td>
            <td>{{$loan->name}}</td>
            <td>{{$loan->branch_id}}</td>
            <td>{{$loan->date_out}}</td>
            <td>{{$loan->due_date}}</td>
            <td>
                <a href="{{ route('loan.check-in', [
                  'book_id' => $loan->book_id, 'branch_id' => $loan->branch_id, 'card_no' => $loan->card_no
                ])}}">
                  @if (isset($loan->date_in))
                  <button class="btn btn-primary" type="submit" disabled>Check-In</button>
                  @else
                  <button class="btn btn-primary" type="submit">Check-In</button>
                  @endif
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
    @endif
  </table>
<div>
@endsection