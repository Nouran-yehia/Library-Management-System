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
    </div>
    <div class="container" style="margin-left:23rem;">
        <form action="{{ url('Book/search') }}" method="POST" class="form-inline">
                @csrf
                <!-- {{method_field('GET')}} -->
                <label for="search_param" style="margin-right:2rem;">Search By</label>
                <select name="search_param" class="form-control form-control-m" data-width="fit">
                        <option value="title" class="dropdown-item" >Title</option>
                        <option value="author" class="dropdown-item">Author</option>
                </select>
                <input type="text" name="search_text" class="form-control form-control-m ml-3 w-50" placeholder="Search" aria-label="Search">
                <button type="submit" class="btn btn-primary" style="margin-left:0.5rem;">Search</button>
        </form>
    </div>
    <div class="container d-flex p-2 bd-highlight flex-wrap justify-content-around align-items-stretch">

        @if(count($data) < 1)
               
            <div class="container"><h1>There are no books available</h1><a href="/Book/create">Create one?</a></div>
        @endif
        <div class="form-group" class="form-control">

            <form method="Get" action="{{route('Book.category')}}">
              
                <select name="category">
                    <option value="" selected>Choose a category</option>
                    <option value="all" >All Category</option>
                    @foreach ($categories as $item)
                    <option type="submit" value="{{$item->id}}">{{$item->category_name}}</option>
                    
                    @endforeach
                </select>
                <input type="submit" class="btn btn-primary" value="Filter"> 
            </form>
            
        </div>
      

        @foreach ($data as $book)
        
            <div class="card align-self-stretch" style="width: 18rem;">
            <img src="storage/images/{{$book->cover}}" class="card-img-top" alt="{{$book->description}}">
                <div class="card-body">
                <h3 class="card-title">{{$book->title}}</h3>
                  <p class="card-text">{{$book->description}}</p>
                  <p class="card-tex">Category: {{$book->category->category_name}}</p>
                  <p class="card-tex">Price: {{$book->price}}</p>
                    @if ($book->num_of_copies < 1)
                        <p class="card-tex">Number Of Copies: 0</p>
                    @else
                        <p class="card-tex">Number Of Copies: {{$book->num_of_copies}}</p>
                    @endif

                    @if(Auth::user()->role == 1)
                        <div class="container d-flex p-2 bd-highlight justify-content-around align-items-stretch">
                            <a class="btn btn-primary"href="{{route('Book.edit' , $book->id)}}">Edit</a>
                            <form method="POST" action="{{route('Book.destroy', $book->id)}}">
                                @method('DELETE')
                                <input type="submit" class="btn btn-danger" value="Delete"> 
                                @csrf 
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection