{% use '_includes' %}
{% use '_widget' %}
{% import '_functions' as func %}
{% set url = get_uri_segments() %}
{% set signin,layout = func.signin()|trim,func.layout()|trim %}
{% set run = get_data('forum')[0].data|json_decode %}
{% set title = 'Đăng nhập' %}
{% if signin %}
<script language="javascript" type="text/javascript"> 
window.location.href="/index.php"; 
</script> 
<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/index.php">
{% else %}
{{block('head')}}
{% set user=get_post('user') %}
{% set pass=get_post('pass') %}
{% set widget_content %}
{% if layout=='wap' %}<center><h2>Đăng nhập</h2></center>{% endif %}
<section class="widget"><ul class="widget-list">
{% if request_method()|lower == "post" %} 
{% if user and pass %}
{% if get_data_count('user_'~func.rwurl(user))==0 %}
<li style="color:red">Tài khoản không tồn tại.</li>
{% else %}
{% if func.get('user_'~func.rwurl(user),'pass')!=func.ma_hoa(pass)|trim %}
<li style="color:red">Mật khẩu không đúng.</li>
{% else %}
<li style="color:green">Đăng nhập thành công.</li>
{{set_cookie('token',func.get('user_'~func.rwurl(user),'token')|trim)}} 
<script language="javascript" type="text/javascript"> 
window.location.href="/"; 
</script> 
<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/">
{% endif %}
{% endif %}
{% else %}
<li style="color:red">Vui lòng điền đầy đủ thông tin!</li>
   {% endif %}
{% endif %}
<form method="post" action="">
<h3>Tài khoản</h3>
<li><input class="form-control" type="text" name="user" placeholder="Nhập tài khoản" value="{{get_post('user')}}" autofocus></li>
<h3>Mật khẩu</h3>
<li><input class="form-control" type="password"  name="pass" placeholder="Nhập mật khẩu" autofocus></li>
<p><center><input class="submit-log" type="submit" name="submit" value="Đăng nhập"></center></p>
</form>
</ul></section>
{% endset %}
{{block('sidebar_left')}}
{% set widget_content %}{{block('sidebar_right_contentNotLogin')}}{% endset %}
{{block('sidebar_right')}}
{{block('end')}}
{% endif %}