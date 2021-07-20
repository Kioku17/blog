{% use '_includes' %}
{% use '_widget' %}
{% import '_functions' as func %}
{% from 'function.twig' import slug,description,time %}
{% from 'func.twig' import mi_get,get,html_decode %}
{% set layout = func.layout()|trim %}
{% set url = get_uri_segments() %}
{% set signin = func.signin()|trim %}
{% set user = get_data('user_'~signin)[0].data|json_decode %}
{% set run = get_data('forum')[0].data|json_decode %}
{{block('head')}}
<div class="grid-3-4" id="main" role="main">
{% set data = mi_get('show_blog')|split(' @ ')|reverse  %}
{% set idfirst = mi_get('show_blog')|split(' @ ')|reverse[0]|trim %}
{% set per = '10' %}
{% set total=data|length-1 %}
{% set page_max=total//per %}
{% if total//per != total/per %}
{% set page_max=total//per+1 %}
{% endif %}
{% set url = get_uri_segments() %}
{% set p=get_get('p')|default(1) %}
{% if p matches '/[a-zA-z]|%/' or p<1 %}
{% set p=1 %}
{% endif %}
{% if p>page_max %}
{% set p=page_max %}
{% endif %}
{% set st=p*per-per %}
{% if total == '0' %}
{% else %}
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
{% set cat2 = get('blog_'~i,'content')|split('[search]')[1]|split('[/search]')[0] %}
{% set pre = name|split('[')[1]|split(']')[0] %}
<article class="post type-post" > <h2 class="post-title"><a href="{{current_url|split('/').0~"//"~current_url|split('/').2}}/library/{{id}}-{{slug}}/">{% if pre!='' %}{{name|replace({(pre):'','[':'',']':''})}}{% else %}{{name}}{% endif %}</a></h2> <ul class="post-meta"> <li><i class="fa fa-book" aria-hidden="true"></i> Thể loại: {% set cat_hide %}{% if ten|split('[')[1]|split(']')[0]!='' or content|split('[search]')[1]|split('[/search]')[0]!='' %}{% for search in cat2|lower|split(', ') %}{% if (ten|split('[')[1]|split(']')[0])|lower != search %}{{search|capitalize}}{% if loop.last == false %}, {% endif %}{% endif %}{% endfor%}{% if (ten|split('[')[1]|split(']')[0])|lower != '' and (cat2|split(', ')|length-1) >= '1' and (ten|split('[')[1]|split(']')[0])|lower not in cat2|split(', ') %}, {% endif %}{{ten|split('[')[1]|split(']')[0]|lower|capitalize}}{% else %} Chưa phân loại{% endif %}{% endset %}{{cat_hide|replace({", , ":", "})}}</li> <li><i class="fa fa-clock-o" aria-hidden="true"></i> Đăng lúc: {{time(time)}}</li></ul> <div class="post-content"> <p>{{description}}...</p> </div> </article>
{% endfor %}
{{func.paging(''~url|join('/')~'?p',p,page_max)}}
{% endif %}
</div>
{% set widget_content %}{{block('sidebar_right_content')}}{% endset %}
{{block('sidebar_right')}}
{{block('end')}}