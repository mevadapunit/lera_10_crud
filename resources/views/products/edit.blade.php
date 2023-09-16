@extends('products.layout')
   
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Product</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
            </div>
        </div>
    </div>
   
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
  
    <form action="{{ route('products.update',$product->id) }}" method="POST">
        @csrf
        @method('PUT')
   
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $product->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Detail:</strong>
                    <textarea class="form-control" style="height:150px" name="detail" placeholder="Detail">{{ $product->detail }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <strong>Category:</strong>
                <select name="category[]" multiple="multiple" id="Category" class="form-select">
                    @foreach ($categories as $category)
                    <option value="option1" @if($category == 'option1') selected @endif>Option 1</option>
                    <option value="option2" @if($category == 'option2') selected @endif>Option 2</option>
                    <option value="option3" @if($category == 'option3') selected @endif>Option 3</option>
                    @endforeach
                </select>
            </div>

            @if ($product->product_transactions->count() > 0)
                <h2>Images</h2>
                <ul>
                    @foreach ($product->product_transactions as $transaction)
                        <li>
                             <img src="{{asset('uploads/product/'.$transaction->image_path)}}" alt="Product Image"> 
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No images available for this product.</p>
            @endif

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit"  class="btn btn-primary">Submit</button>
            </div>
        </div>
   
    </form>
<script src="https://code.jquery.com/jquery-3.7.1.slim.js" integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $('#Category').select2();
</script>

@endsection