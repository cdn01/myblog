<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <title>栅格</title>
        <meta http-equiv=Content-Type content="text/html;charset=utf-8" />
        <link type="text/css" rel="stylesheet" media="screen" href="dist/css/bootstrap.min.css">
        <script type="text/javascript" src="dist/js/bootstrap.min.js"></script>
    </head>
    <body>
        <b>col-lg-*用法</b>  
        <br/> 
        <div class="row show-grid">
          <div class="col-lg-8">.col-lg-8</div>
          <div class="col-lg-4">.col-lg-4</div>
        </div>
        <br/>         
        <b>col-md-*用法</b>
        <div class="row show-grid">
          <div class="col-md-1">.col-md-1</div>
          <div class="col-md-1">.col-md-1</div>
          <div class="col-md-1">.col-md-1</div>
          <div class="col-md-1">.col-md-1</div>
          <div class="col-md-1">.col-md-1</div>
          <div class="col-md-1">.col-md-1</div>
          <div class="col-md-1">.col-md-1</div>
          <div class="col-md-1">.col-md-1</div>
          <div class="col-md-1">.col-md-1</div>
          <div class="col-md-1">.col-md-1</div>
          <div class="col-md-1">.col-md-1</div>
          <div class="col-md-1">.col-md-1</div>
        </div>
        <br/> 
        <div class="row show-grid">
          <div class="col-md-8">.col-md-8</div>
          <div class="col-md-4">.col-md-4</div>
        </div>
        <br/> 
        <b>col-sm-*用法</b>
        <div class="row show-grid">
          <div class="col-sm-8">.col-sm-8</div>
          <div class="col-sm-4">.col-sm-4</div>
        </div>
        <br/>             
        <b>col-xs-*用法</b>
        <div class="row show-grid">
          <div class="col-xs-8">.col-xs-8</div>
          <div class="col-xs-4">.col-xs-4</div>
        </div>    
        <br/>     
        <b>列偏移: col-md-offset-*</b>
        <div class="row show-grid">
          <div class="col-md-4">.col-md-4</div>
          <div class="col-md-4 col-md-offset-4">.col-md-4 .col-md-offset-4</div>
        </div>
        <div class="row show-grid">
          <div class="col-md-3 col-md-offset-3">.col-md-3 .col-md-offset-3</div>
          <div class="col-md-3 col-md-offset-3">.col-md-3 .col-md-offset-3</div>
        </div>
        <div class="row show-grid">
          <div class="col-md-6 col-md-offset-3">.col-md-6 .col-md-offset-3</div>
        </div>
        <br/>     
        <b>嵌套列: 嵌套row所包含的列加起来应该等于12</b>
        <div class="row show-grid">
            <div class="col-md-9">
                Level 1: .col-md-9
                <div class="row show-grid">
                    <div class="col-md-6">Level 2: .col-md-6</div>
                    <div class="col-md-6">Level 2: .col-md-6</div>
                </div>
            </div>
        </div>
        <br/>     
        <b>列排序: .col-md-push-*和.col-md-pull-*</b>    
        <div class="row show-grid">
          <div class="col-md-9 col-md-push-3">.col-md-9 .col-md-push-3</div>
          <div class="col-md-3 col-md-pull-9">.col-md-3 .col-md-pull-9</div>
        </div>        
    </body>
</html>