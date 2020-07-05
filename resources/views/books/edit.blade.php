@extends('layout')

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="card uper">
  <div class="card-header">
    Edit Book Data
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
      <form method="post" action="{{ route('books.update', $book->book_id ) }}">
          <div class="form-group">
              @csrf
              @method('PATCH')
              <label for="country_name">Title:</label>
              <input type="text" class="form-control" name="title" value="{{ $book->title }}"/>
          </div>
          <div class="form-group">
              <label for="symptoms">Publisher :</label>
              <textarea rows="5" columns="5" class="form-control" name="publisher">{{ $book->publisher }}</textarea>
          </div>
          <div class="form-group">
              <label for="cases">Year :</label>
              <input type="text" class="form-control" name="year" value="{{ $book->year }}"/>
          </div>
          <button type="submit" class="btn btn-primary">Edit Data</button>
      </form>
  </div>
</div>
@endsection