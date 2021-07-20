{% use '_includes' %}
{% use '_widget' %}
{% from 'func.twig' import mi_get,mi_up,k_del,get,html_decode %}
{% from 'function.twig' import add,mi_add,slug,time,description %}
{% import '_functions' as func %}
{% set url = get_uri_segments() %}
{% set data = mi_get('show_blog')|split(' @ ')|reverse  %}
{% set domain = current_url|split('/').0~"//"~current_url|split('/').2 %}
{% if get_get('key') and not get_get('author') %}
{% set data1 %}{% for i in data %}{% set key = get_get('key') %}
{% if key|length>'0' and key|lower in get('blog_'~i|trim,'title')|trim|lower or key|length>'0' and key|lower in get('blog_'~i|trim,'content')|trim|lower %}{% if loop.last==false %}{{i|trim}}.{% endif %}{% endif %}{% endfor %}{% endset %}
{% elseif get_get('author') and not get_get('key') %}
{% set data1 %}{% for i in data %}{% set key = '[author]'~get_get('author')~'[/author]' %}
{% if key|length>'0' and key|lower in get('blog_'~i|trim,'title')|trim|lower or key|length>'0' and key|lower in get('blog_'~i|trim,'content')|trim|lower %}{% if loop.last==false %}{{i|trim}}.{% endif %}{% endif %}{% endfor %}{% endset %}
{% endif %}
{% set count = data1|split('.')|length-1 %}
{% if get_get('key')!='' and not get_get('author') %}
{% set title = 'Kết quả tìm kiếm "'~get_get('key')~'" ' %}
{% elseif get_get('author')!='' and not get_get('key') %}
{% set title = 'Tác giả: '~get_get('author')~' ' %}
{% else %}
{% set title = 'Tìm kiếm' %}
{% endif %}
{% set data=data1|split('.') %}
 {% set total=data|length-1 %} 
{% set per = '10' %}
 {% set page_max=total//per %}
{% if total//per != total/per %}
{% set page_max=total//per+1 %}
{% endif %}
{% set p=get_get('p')|default('1') %} 
{% if p matches '/[a-zA-z]|%/' or p<1 %}
{% set p=1 %}
{% endif %}
{% if p>page_max %}
{% set p=page_max %}
{% endif %}
{% set st=p*per-per %}
{% set description %}{% for id in data|slice(0,total)|slice(st,per) %}
{% set blog = 'blog_'~id|trim %}{% set title2 = get(blog,'title') %}{% set pre = title2|split('[')[1]|split(']')[0] %}{% if pre!='' %}{{title2|replace({(pre):'','[':'',']':''})}}{% else %}{{title2}}{% endif %}, {% endfor %}{% endset %}
{% set description = description|slice(0,250)|trim %}
{% if count > '0' %}
{{block('head')}}
{% set widget_content %}
<section class="widget"><center><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Có {{count}} kết quả được tìm thấy</center></section>
{% for i in data|slice(0,total)|slice(st,per) %}
{% set name = get('blog_'~i,'title') %}
{% set ten = name %}
{% set id = get('blog_'~i,'id')|trim %}
{% set category = get('blog_'~i,'category') %}
{% set slug = get('blog_'~i,'slug') %}
{% set cat = get('category_'~category,'ten') %}
{% set catid = get('category_'~category,'id') %}
{% set catslug = get('category_'~category,'slug') %}
{% set time = get('blog_'~i,'time')|trim %}
{% set view = get('blog_'~i,'view')|trim %}
{% set content = get('blog_'~i,'content','raw') %}
{% set mota = description(content) %}
{% set description = (description(content|split('[desc]')[1]|split('[/desc]')[0])|default(mota))|striptags|slice(0,100) %}
{% set thumb = get('blog_'~i,'thumb')|trim %}
{% set cmt = get('blog_'~i,'comment_num')|trim %}
{% set cat2 = get('blog_'~i,'content')|split('[search]')[1]|split('[/search]')[0] %}
{% set pre = name|split('[')[1]|split(']')[0] %}
<article class="post type-post" > <h2 class="post-title"><a href="{{current_url|split('/').0~"//"~current_url|split('/').2}}/library/{{id}}-{{slug}}/">{% if pre!='' %}{{name|replace({(pre):'','[':'',']':''})}}{% else %}{{name}}{% endif %}</a></h2> <ul class="post-meta"> <li><i class="fa fa-book" aria-hidden="true"></i> Thể loại: {% set cat_hide %}{% if ten|split('[')[1]|split(']')[0]!='' or content|split('[search]')[1]|split('[/search]')[0]!='' %}{% for search in cat2|lower|split(', ') %}{% if (ten|split('[')[1]|split(']')[0])|lower != search %}{{search|capitalize}}{% if loop.last == false %}, {% endif %}{% endif %}{% endfor%}{% if (ten|split('[')[1]|split(']')[0])|lower != '' and (cat2|split(', ')|length-1) >= '1' and (ten|split('[')[1]|split(']')[0])|lower not in cat2|split(', ') %}, {% endif %}{{ten|split('[')[1]|split(']')[0]|lower|capitalize}}{% else %} Chưa phân loại{% endif %}{% endset %}{{cat_hide|replace({", , ":", "})}}</li> <li><i class="fa fa-clock-o" aria-hidden="true"></i> Đăng lúc: {{time(time)}}</li></ul> <div class="post-content"> <p>{{description}}...</p> </div> </article>
{% endfor %}
{% if page_max>per %}
{% set page_max=per %} 
 {% endif %} 
 {{func.paging(url|join('/')~'?key='~get_get('key')~'&p',p,page_max)}}
{% endset %}
{{block('sidebar_left')}}

{% set widget_content %}{{block('sidebar_right_content')}}{% endset %}
{{block('sidebar_right')}}

{{block('end')}}
{% else %}
{% include 'index.php' %}
{% endif %}