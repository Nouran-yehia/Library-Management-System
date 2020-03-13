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
    <div class="container">
        <a href="rate">Sort by rate</a>
        <a href="date">Sort by Date</a>  
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
    </div>

    <div class="container d-flex p-2 bd-highlight flex-wrap justify-content-around align-items-stretch">
        {{-- <div class="container">
            @if(count($Books) < 1)
                <h1>There are no books available</h1>
            @endif
            @if(Auth::user()->role == "admin")
                    <a href="Book/create">Create a Book.</a>
            @endif
        </div> --}}
        
        @if(count($data) < 1)
        
        <div class="container"><h1>There are no books available</h1><a href="/Book/create">Create one?</a></div>
        @endif
        
        {{-- @foreach ($Books as $book) --}}
      
        @foreach ($data as $book)
        
            <div class="card align-self-stretch" style="width: 18rem;">
            <img src="storage/images/{{$book->cover}}" class="card-img-top" alt="{{$book->description}}">
                <div class="card-body">
                <h3 class="card-title">{{$book->title}}</h3>
                  @if($book->total_rating > 0 )
                    Total rating: {{$book->total_rating}}
                  @endif
                  <p class="card-text">Description: {{$book->description}}</p>
                  <p class="card-tex">Category: {{$book->category->category_name}}</p>
                  <p class="card-tex">Price: {{$book->price}}</p>
                    @if ($book->num_of_copies < 1)
                        <p class="card-tex">Number Of Copies: 0</p>
                    @else
                        <p class="card-tex">Number Of Copies: {{$book->num_of_copies}}</p>
                    @endif
                    
        <!-- Button trigger modal -->
        @if($book->num_of_copies >0)
                <button class="btn btn-info" data-mytitle="{{$book->title}}" data-mydescription="{{$book->description}}" data-bookid={{$book->id}} data-toggle="modal" data-target="#edit{{$book->id}}">Lease</button>
      <!-- Modal -->
      <div class="modal fade" id="edit{{$book->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">{{$book->title}}</h4>
            </div>
            
            <form  method="POST" action="{{route('Book.lease')}}">
                    {{method_field('post')}}
                    {{csrf_field()}}
                <div class="modal-body">
                        <input type="hidden" name="book_id" id="cat_id" value={{$book->id}}>
                       Number of days: <input type="number" name="number_of_days" max="60" min="1"/>                   
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Lease</button>
                </div>
            </form>
        </div>
        </div>
    </div>   
    @else
    <p class="alert alert-info">Book is not available</p>
    @endif 
    
                    @if(Auth::user()->role == "admin")
                        <div class="container d-flex p-2 bd-highlight justify-content-around align-items-stretch">
                            <a class="btn btn-primary"href="{{route('Book.edit' , $book->id)}}">Edit</a>
                            <form method="POST" action="{{route('Book.destroy', $book->id)}}">
                                @method('DELETE')
                                <input type="submit" class="btn btn-danger" value="Delete"> 
                                @csrf 
                            </form>
                        </div>
                    @endif

                    @if(Auth::user()->role == "user")
                        
                        @php
                            $userRatePrinted = false;
                        @endphp
                      
                        @foreach ($RatedBooks as $ratedBook)
                        
                            @if( $book->id == $ratedBook->book_id && Auth::id() == $ratedBook->user_id )
                                <p class="card-tex">You rated this book: {{$ratedBook->rating}}</p>
                                @php
                                    $userRatePrinted = true;   
                                @endphp
                            @endif
                            
                        @endforeach
                       
                        
                        @if( !$userRatePrinted )                               
                            <form method="POST" action="{{route('UserRateBook.store')}}">
                                <input type="hidden" value="{{$book->id}}" name="bookId">
                                <input type="hidden" value="{{Auth::id()}}" name="userId">
                                <input type="number" min="1" max="5" name="rate" value="3">
                                <input type="submit" class="btn btn-primary" value="Rate"> 
                                @csrf 
                            </form>
                            @php
                                $userRatePrinted= false;
                            @endphp
                        @endif

                    @endif
                </div>
            </div>
        @endforeach
        {{-- @endforeach --}}
      
    </div>
@endsection
