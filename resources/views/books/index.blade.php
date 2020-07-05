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
          <td>Publisher</td>
          <td>Year</td>
          <td>Checkout</td>
          <td colspan="2">Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($books as $book)
        <tr>
            <td>{{$book->book_id}}</td>
            <td>{{$book->title}}</td>
            <td>{{$book->year}}</td>
            <td>{{$book->publisher}}</td>
            <td>
              <a href="{{ route('books.checkout', ['book_id' => $book->book_id, 'branch_id' => $book->branch_id])}}">
                <button class="btn btn-info" type="submit">Checkout</button>
              </a>
            </td>
            <td><a href="{{ route('books.edit', $book->book_id)}}" class="btn btn-primary">Edit</a></td>
            <td>
                <form action="{{ route('books.destroy', $book->book_id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
<div>
@endsection