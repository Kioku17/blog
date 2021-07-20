{% use '_includes' %}
{% use '_widget' %}
{% import '_functions' as func %}
{% from 'function.twig' import mi_add %}
{% from 'func.twig' import get,mi_get,html_decode %}
{% set url = get_uri_segments() %}
{% set signin,layout = func.signin()|trim,func.layout()|trim %}
{% set run = get_data('forum')[0].data|json_decode %}
{% set title = 'Đăng ký tài khoản' %}
{% if signin %}
<script language="javascript" type="text/javascript"> 
window.location.href="/"; 
</script> 
<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/">
{% else %}
{{block('head')}}
{% if layout=='wap' %}<center><h2>Đăng ký</h2></center>{% endif %}
{% set widget_content %}
<section class="widget"><ul class="widget-list">
{% if run.account <= '500' %}
{# kiểm tra và lưu tài khoản #}
{% set user = get_post('user') %}
{% set pass = get_post('pass') %} 
{% set repass = get_post('repass') %} 
{% set name_user = get_post('name') %} 
{% set sex = get_post('sex') %} 
{% set token=func.token()|trim %}
{% set registration %}
<form method="post" action="">
<h3>Tên tài khoản</h3>
<li><input class="form-control" type="text" name="user" value="" required></li>
<h3>Tên hiển thị <font style="color:gray">(không bắt buộc)</font></h3>
<li><input class="form-control" type="text" name="name" value="" required></li>
<li><b>Giới tính:</b> <select name="sex">{% for sex in ['male','female','lgbt'] %}<option value="{{sex}}">{% if sex=='male' %}Nam{% elseif sex=='female' %}Nữ{% else %}Khác{% endif %}</option>{% endfor %}</select></li>
<h3>Mật khẩu</h3>
<li><input class="form-control" type="password"  name="pass" value=""></li>
<h3>Nhập lại mật khẩu</h3>
<li><input class="form-control" type="password" name="repass" value=""></li>
<p><center><input class="submit-log" type="submit" name="submit" value="Đăng ký tài khoản"></center></p>
</form>
{% endset %}
{% if request_method()|lower == "post" %}
{% if user and pass and repass and sex %} 
{% if pass!=repass %}
<li style="color:red">Mật khẩu xác nhận không đúng.</li>
{% else %}
{% if get_data_count('user_'~func.rwurl(user))>0 %} 
<li style="color:red">Tài khoản đã tồn tại.</li>
{{registration}}
{% elseif user|length<3 or user|length>15 %}
<li style="color:red">Tài khoản không dài quá 15 kí tự, tối thiểu 3 kí tự</li>
{{registration}}
{% elseif name_user|length>15 %}
<li style="color:red">Tên hiển thị không dài quá 15 kí tự</li>
{{registration}}
{% else %} 
{% if user matches '/^[a-zA-Z0-9\\-\\_]+[a-zA-Z0-9\\-\\_]$/' %} 
{# ============ #}
{% if func.rwurl(user)=='admin' %}{% set rUser = '9' %}{% elseif func.rwurl(user)=='apple' %}{% set rUser = '3' %}{% else %}{% set rUser = '0' %}{% endif %}
{% set data={"id":run.account|trim+1,"name":name_user|default(user),"nick":user,"pass":func.ma_hoa(pass)|trim,"right":rUser,"xu":"0","luong":"0","avt":"x1","token":token,"reg":"now"|date("U"),"like":0,"postforum":0,"sex":sex,"rename":3} %}
{% set status = save_data('user_'~func.rwurl(user),data|json_encode) %}
<h3>Dữ liệu thông tin</h3>
<li><b>Tài khoản:</b> {{user}} </li>
<li><b>Mật khẩu:</b> {{pass}} </li>
<li><a href="/">[ Về trang chủ ]</a></li>
{% set old_token = html_decode(get('token'))|replace({'”':'"'}) %}
{% set new_token = old_token|json_decode|merge({(token):(user)}) %}
{{mi_add('token',(new_token))}}
{{func.add('forum','new_mem',user)}}
{{func.add('forum','time_reg',"now"|date("U")+50)}}
{{func.add('forum','account',run.account|trim+1)}}
{{func.up('member',func.rwurl(user),'up')}}
{{set_cookie('token',token)}}
{# ============ #}
{% else %}
<li style="color:red">Tài khoản không được chứa ký tự đặc biệt.</li>
{{registration}}
{% endif %} 



{% endif %}
{% endif %}
{% else %}
<li style="color:red">Vui lòng điền đầy đủ thông tin và xác minh Tôi không phải là người máy!</li>
{{registration}}
{% endif %}
{% else %}
{{registration}}
{% endif %}
{% else %}
<li>Đăng ký tạm thời đóng cửa !</li>
{% endif %}
</ul></section>
{% endset %}
{{block('sidebar_left')}}
{% set widget_content %}{{block('sidebar_right_contentNotLogin')}}{% endset %}
{{block('sidebar_right')}}
{{block('end')}}
{% endif %}