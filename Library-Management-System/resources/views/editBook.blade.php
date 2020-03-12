@extends('layouts.app')
@section("content")
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('message'))
        <div class="alert alert-success" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <form method="POST" action="{{route('Book.update', $book->id)}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
            <div class="form-group">
            <label>Book's Title</label>
            <input type="text" class="form-control" value="{{$book->title}}" placeholder="Title" name="title">
            </div>

            <div class="form-group">
                <label>Book's Author</label>
                <input type="text" class="form-control" value="{{$book->author}}" placeholder="Enter Author" name="author">
            </div>

            <div class="form-group">
                <label>Book's Description</label>
                <input type="text" class="form-control" value="{{$book->description}}" placeholder="Enter Book" name="description">
            </div>

            <div class="form-group">
                <label>Book's Price</label>
                <input type="number" class="form-control" value="{{$book->price}}" placeholder="Enter Price" min="1" name="price">
            </div>

            <div class="form-group">
                <label>Number of copies</label>
                <input type="number" class="form-control" value="{{$book->num_of_copies}}" placeholder="Enter #copies" min="0" name="num_of_copies">
            </div>

            <div class="form-group">
                <label>Book's Category</label>
                <select name="category">
                    @foreach ($categories as $item)
                        @if($item->id == $book->id)
                            <option value="{{$item->id}}" selected>{{$item->category_name}}</option>
                        @else
                            <option value="{{$item->id}}">{{$item->category_name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="exampleFormControlFile1">Enter book cover</label>
                <input type="file" class="form-control-file" name="cover">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
