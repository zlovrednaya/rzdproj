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
    <!---script src="https://code.jquery.com/jquery-3.6.0.min.js"></script--->
    <!--- script src="step1.js"></script>-->
    <style>
    </style>
  </head>
  <body>

<form action="/result" method="post"> 
@csrf
<div class="step2">
  Press Continue to receive result
</div>
<div class="step2 btn_input">
  <button class="btn btn-outline-secondary" type="submit" id="continue_button">Continue</button>
</div>
</form>
  </body>
</html>