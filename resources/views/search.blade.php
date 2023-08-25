
<!doctype html>
<html>
  <head>


    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <link rel="stylesheet" href="/css/app.css">
    <title>checktrains</title>
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!---script src="https://code.jquery.com/jquery-3.6.0.min.js"></script--->
    <!--- script src="step1.js"></script>-->
    <style>
    </style>
  </head>
  <body>
    <br>

    <div class="container">
    <h5>  Перейти к поиску </h5>
      <form action="/step2" method="post"> 
      @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Select train" id="train" aria-label="Select train station" aria-describedby="button-addon2" autocomplete="on" name="train" autocomplete="on">
        </div>
        <div>
          <input type="date" class="form-control" aria-describedby="button-addon2" id="departure_date" name="departure_date" >
        </div>
        <br>
        <div class="btn_input">
          <button class="btn btn-outline-secondary justify-content-sm-center" type="submit" id="search_button">Search</button>
        </div>
      </form>
      @if($errors->any())
      <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        {{$error}}
        @endforeach
      </div>
      @endif
    </div>
    @if(isset($previous_vars))
    <div class="previous">
          <u>Предыдущий поиск</u> <br> Поезд: {{$previous_vars[0]}} <br> Дата: {{$previous_vars[1]}}
        </div>
    @endif


<script type="text/javascript" src="/js/hide_script.js"></script>
<script type="text/javascript">
$.post({
    url: "http://b2b-demo.n3health.ru/emk/PixService.svc/soap12",
    method:"POST",
    headers: {
    "Content-Type": "text/xml",
    "SOAPAction": "\"http://tempuri.org/IPixService/GetPatient\"",
     },
    dataType: "xml",
contentType: "text/xml;charset=UTF-8",
crossDomain: true,

    data:"<soap:Envelope xmlns:soap=\"http://www.w3.org/2003/05/soap-envelope\" xmlns:tem=\"http://tempuri.org/\" xmlns:emk=\"http://schemas.datacontract.org/2004/07/EMKService.Data.Dto\"><soap:Header/><soap:Body><tem:GetPatient> <tem:guid>47c05c63-fbdf-46eb-839b-ea5bd57ce5a5</tem:guid><tem:idLPU>955e0157-e155-4654-aaa9-4d56d5cc0f8a</tem:idLPU>        <tem:patient>            <emk:Addresses>               <emk:AddressDto>                  <emk:IdAddressType>1</emk:IdAddressType>                  <emk:StringAddress>СПб, ул.Первомайская, д.38</emk:StringAddress>               </emk:AddressDto>            </emk:Addresses>            <emk:BirthDate>1999-09-10</emk:BirthDate> <emk:Documents>              <emk:DocumentDto> <emk:DocN>66242</emk:DocN><emk:DocS>0324</emk:DocS><emk:IdDocumentType>14</emk:IdDocumentType><emk:ProviderName>самое тру фмс</emk:ProviderName></emk:DocumentDto></emk:Documents><emk:FamilyName>Андреева</emk:FamilyName><emk:GivenName>Ангелина</emk:GivenName><emk:IdPatientMIS>11003900</emk:IdPatientMIS><emk:Sex>2</emk:Sex></tem:patient></tem:GetPatient></soap:Body></soap:Envelope>",
success: function(data, textStatus ){
         console.log('STATUS: '+textStatus);
     },
     error: function(xhr, textStatus, errorThrown){
       console.log('STATUS: '+textStatus+'\nERROR THROWN: '+errorThrown);
     }});
</script>
  </body>
</html>