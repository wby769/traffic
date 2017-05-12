<!DOCTYPE html>  
<html lang="en">  
<head>  
<base href="<?=base_url()?>" />
    <meta charset="UTF-8">  
    <title>登陆</title>  
    <style type="text/css">
        html{   
            width: 100%;   
            height: 100%;   
            overflow: hidden;   
            font-style: sans-serif;   
        }   
        body{   
            width: 100%;   
            height: 100%;   
            font-family: 'Open Sans',sans-serif;   
            margin: 0;   
            background-color: #23262E;   
            /*background-color: #333;   */
        }   
        #login{   
            position: absolute;   
            top: 50%;   
            left:50%;   
            margin: -150px 0 0 -150px;   
            width: 300px;   
            height: 300px;   
        }   
        #login h1{   
            color: #fff;   
            text-shadow:0 0 10px;   
            letter-spacing: 1px;   
            text-align: center;   
        }   
        h1{   
            font-size: 2em;   
            margin: 0.67em 0;   
        }   
        input{   
            width: 278px;   
            height: 18px;   
            margin-bottom: 10px;   
            outline: none;   
            padding: 10px;   
            font-size: 13px;   
            color: #fff;   
            text-shadow:1px 1px 1px;   
            border-top: 1px solid #312E3D;   
            border-left: 1px solid #312E3D;   
            border-right: 1px solid #312E3D;   
            border-bottom: 1px solid #56536A;   
            border-radius: 4px;   
            background-color: #333;   
        }   
        .but{ 
            width: 300px;   
            min-height: 20px;   
            display: block;   
            background-color: #4a77d4;   
            border: 1px solid #3762bc;   
            color: #fff;   
            padding: 9px 14px;   
            font-size: 15px;   
            line-height: normal;   
            border-radius: 5px;   
            margin: 0;   
        }  
        input:-webkit-autofill {
          -webkit-box-shadow: 0 0 0px 1000px #fff inset !important;
        }
    </style>
</head>  
<body>  
    <div id="login">
        <h1>后台登录</h1>
        <form> 
            <input type="text" placeholder="用户名" autocomplete="off" name="username"/></input>  
            <input type="password" placeholder="密码" autocomplete="off" name="password"></input>  
            <p>
        </form>  
        <button class="but">登录</button>  
    </div> 
    <script type="text/javascript" src="public/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="public/layer/layer.js"></script>
    <script>
        $('button').click(doDeal);
        function doDeal(){
            if(!$(':text[name=username]').val()){
                layer.tips('请输入用户名', ':text[name=username]');
                return false;
            }
            if(!$(':password[name=password]').val()){
                layer.tips('请输入密码', ':password[name=password]');
                return false;
            }
            $.post(
                "<?=base_url('nav/login/tc')?>",
                $('form').serialize()+'&check=JSON',
                function(data){
                    if(data == 'success')
                        location.href='tc';
                    else
                        layer.msg('用户名或密码错误!',function(){});
            });
        }

        $(window).keyup(function(event){
            if(event.which == 13)
                doDeal();
        });
    </script>
</body>  
</html>