<html>
    <head>    
    <!--- возможно надо то что внизу файл перенести в паблик--->    
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
   
    </head>
<body>
  
@if (isset($result_view))
  <div class="train_info">
  @foreach ($result_view['train_info'] as $nums=>$train_info)
    {{$train_info}} <br> 
  @endforeach 
</div>
<div class='places_block'>
@if ($result_view['places'] != null)
@for ($i=0;$i<count($result_view['places']);$i++)
  <div class='places'>
    <i class='bi bi-moon-fill'></i>   
    <div class='pl-num'>{{$result_view['places'][$i]}}</div>  
    <i class='bi bi-minecart'></i>
    <div class='pl-car'>{{$result_view['cars'][$i]}}</div> 
    <i class='bi bi-cash'></i> 
    <div class='pl-cash'>{{$result_view['tariffs'][$i]}} ₽</div> 
    <br>
    </div>     
  @endfor
@else
 <div class='no_places'>Result: No places </div>
 <br>
@endif
@elseif($result_view == null)
 <div class='no_places'><div>Result: No places </div></div>

@endif
</div> 
<form action="/" method="get"> 
      @csrf      
      <div class="btn_input">
          <button class="btn btn-outline-secondary" type="submit" id="search_button">Press to repeate search</button>
        </div>
</form>    


</body>
</html>