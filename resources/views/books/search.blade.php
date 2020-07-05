@extends('layout')

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="uper">
  <!-- Search form -->
  <form method="post" action="{{ route('books.search') }}">
    <div class="form-group">
      @csrf
      <div class="input-group">
        <div class="md-form mt-0">
          <input class="form-control" type="text" name="query" placeholder="Search Book" aria-label="Search">
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
          <td>Title</td>
          <td>Author(s)</td>
          <td>Branch Id</td>
          <td>Branch Name</td>
          <td>Copies Owned</td>
          <td>Book Availablity</td>
          <td colspan="2">Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($books as $book)
        <tr>
            <td>{{$book->book_id}}</td>
            <td>{{$book->title}}</td>
            <td>{{$book->authors}}</td>
            <td>{{$book->branch_id}}</td>
            <td>{{$book->branch_name}}</td>
            <td>{{$book->no_of_copies}}</td>
            <td>{{$book->availability}}</td>
            <td>
              @if ($book->availability == 'In Use' || $book->availability == 'Not Available')
                <a href="{{ route('books.checkout', ['book_id' => $book->book_id, 'branch_id' => $book->branch_id])}}">
                  <button class="btn btn-danger" type="submit" disabled>Out of Stock</button>
                </a>
              @else
                <a href="{{ route('books.checkout', ['book_id' => $book->book_id, 'branch_id' => $book->branch_id])}}">
                  <button class="btn btn-info" type="submit">Checkout</button>
                </a>
              @endif  
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
<div>
@endsection