# ntcode

## framework
1. laravel 5.4+

## install
1. composer require fc/ntcode

## init 

php artisan vendor:publish

## demo controller

```
<?php

// use LLLntcode;

class TestController extends BaseController
{
    
    
    
    public function index(){

        $aTpl = array();
        
        $aTpl['TPL'] = array();
        
        
        return View::make("test" , $aTpl );
    }
    
    
    public function make(){
        echo LLLntcode::make();
    }
    
    public function check(){
        echo LLLntcode::check();
        exit();
    }
    
}

```



## demo view

```
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>test</title>
  

@include('ntcode::header')

</head>

<body>

  



<div class="tncode" ></div>

<script type="text/javascript">
$TN.setUrl("img", "{!! URL::route('test.make', [] ) !!}");
$TN.setUrl("check" , "{!! URL::route('test.check', [] ) !!}");
$TN.onsuccess(function(){
    alert("ok");
});
</script>



</body>

</html>



```

