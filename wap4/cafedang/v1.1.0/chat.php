{% import '_functions' as func %}
{% from '_functions' import get,ago,bbcode %}
{% import 'chat_module_bot' as bot %}
{% set layout = func.layout()|trim %}
{% set login = func.signin()|trim %}
{% set name = login %}
{% set ubot = get_data('user_apple')[0].data|json_decode %}
{% set id = func.get('guestbook')|split('@')[199]|trim %}
{% set msg = get_post('msg') %}
{% set now = "now"|date("U") %}
{% if msg != '' and msg != '\r\n' and msg!=null and msg|length <= '16000' and login %}
{{func.add('user_'~login,'new',msg)}} 
{% if msg and func.get('user_'~login,'old')|trim|raw != func.get('user_'~login,'new')|trim|raw %}
{% set comment = {"name" :name,"time":now,"comment":msg} %}
{% if 'xep chu' not in msg|lower and 'xếp chữ' not in msg|lower %}
{{func.add('chat_'~id,'name',name)}}
{{func.add('chat_'~id,'time',now)}}
{{func.add('chat_'~id,'comment',msg)}} 
{{func.up('guestbook',id,'up') }}
{{func.add('user_'~login,'old',msg)}} 
{% endif %}
{{func.add('user_'~login,'xu',get('user_'~login,'xu')|trim+2)}}
{{func.add('user_'~login,'postguest',get('user_'~login,'postguest')|trim+1)}}
{{func.add('user_'~login,'sm',get('user_'~login,'sm')|trim+1)}}
{{bot.thayphan(msg,login)}}
{{bot.chemgio(msg,login)}}
{{bot.minigame(msg,'apple')}}
{{bot.ott(msg,'apple')}}
{{bot.xepchu(msg,'apple')}}
{% endif %}
{% endif %}
{% set ListChat = func.get('guestbook')|trim|split('@') %}
{% set TotalChat = ListChat|length-1 %} 
{% set ListID %}{% for id in ListChat|slice(0,TotalChat) %}{% set entry = get_data('chat_'~id|trim)[0].data|json_decode %}{% if entry.name %} {{id|trim}} @ {% endif %}{% endfor %}{% endset %}
{% set data = ListID|trim|split('@') %}
{% set total = data|length-1 %}
 {% set page_max=total//10 %}
{% if total//10 != total/10 %}
{% set page_max=total//10+1 %}
{% endif %}
 {% set url=get_uri_segments() %}
{% set p=get_get('page')|default(1) %} 
{% if p matches '/[a-zA-z]|%/' or p<1 %}
{% set p=1 %}
{% endif %}
{% if p>page_max %}
{% set p=page_max %}
{% endif %}
{% set st=p*10-10 %}

{% if login %}

{% if ubot['xc'].time >= "now"|date("U") %}
<div align="center"><big>
{% if ubot['xc'].time=="now"|date("U") %}
Hết giờ !!!
{% else %}
Sắp xếp các chữ sau thành từ hoặc cụm từ hoàn chỉnh:<br/>{{ubot['xc'].quest}}
<br/>{% for k in (ubot['xc'].raw_word)|split('') %}{% if k != ' ' %}<font style="font-size:20px;letter-spacing:6px;font-weight:700">{% if (ubot['xc'].time)-("now"|date("U")) <= (loop.index-2)*(100/(ubot['xc'].raw_word|length)) %}{{k}}{% else %}*{% endif %}</font>{% else %}{{k}}{% endif %}{% endfor %}
<br/>Còn: {{(ubot['xc'].time)-("now"|date("U"))}} giây nữa
{% endif %}
</big></div>
{% endif %}

{% endif %}
<ol class="comment-list">
{% for id in data|slice(0,total)|slice(st,10) %}
{% set entry = get_data('chat_'~id|trim)[0].data|json_decode %}
{% set user='user_'~entry.name %}
{% set info=get_data(user)[0].data|json_decode %}
{% set nd = entry.comment %}
{% set time = entry.time %}
{% set jun = now-time %}
{% if jun > 1 %}
{% if time|date('d','Asia/Ho_Chi_Minh') == 'now'|date('d','Asia/Ho_Chi_Minh') %}
{% set agos = ago(time) %}
{% else %}
{% set agos = time|date("H:i, d/m/Y","Asia/Ho_Chi_Minh")|replace({(now|date("d/m/Y","Asia/Ho_Chi_Minh")):'Hôm nay'}) %}
{% endif %}
{% else %}
{% set agos = 'vừa xong' %}
{% endif %}
{% if entry.name %}
<li id="li-comment-{{id}}" class="comment-body comment-parent comment-odd"> <div id="comment-{{id}}"> <div class="comment-author"> <img class="avatar" src="{{func.avtdefault(entry.name)|trim}}" alt="{{entry.name}}" width="" height="" /> <cite class="fn"><a href="/guestbook/user?name={{entry['name']|trim}}">{% if func.get(user,'ban') =='1' %}<s>{{get(user,'name')}}</s>{% else %}{{func.mau_nick(entry.name,info.right)}}{% endif %}</a> <span name="online">{% if info['on'] < ('now'|date('U')-300) %}<font color="red"><i class="fa fa-toggle-off" aria-hidden="true"></i></font>{% else %}<font color="green"><i class="fa fa-toggle-on" aria-hidden="true"></i></font>{% endif %}</span> </cite> </div> <div class="comment-meta"> {{agos}}</div> {% if info.ban!=null %}Nội dung đã bị ẩn do người này đã vi phạm quy định của weblog{% else %}{{bbcode(nd|raw)}}{% endif %} </div></li>
{% endif %} 
{% endfor %}
</ol>

{% if page_max>20 %}
{% set page_max=20 %} 
 {% endif %} 
{{func.paging('../guestbook/data?page',p,page_max)}}

{% if ubot['xc'].time < "now"|date("U") and ubot['xc'].end != 'yes' %}
{{func.add('chat_'~id,'name','apple')}}
{{func.add('chat_'~id,'time',ubot['xc'].time)}}
{{func.add('chat_'~id,'comment','Rất tiếc, không có ai trả lời đúng câu hỏi vừa rồi. Đáp án đúng là: [b]'~ubot['xc'].word~'[/b]')}} 
{{func.add('user_apple','xc',{"id":ubot['xc'].id|trim,"time":"now"|date("U"),"word":xc.word,"raw_word":xc.raw_word,"end":"yes"})}}
{{func.up('guestbook',id,'up') }}
{{func.add('user_apple','postguest',get('user_apple','postguest')|trim+1)}}
{% endif %}

{% if login and login not in func.get('show_online')|split('@') %}
{{func.up('show_online',login,'up')}}
{{func.add('user_'~login,'on','now'|date('U'))}}
{% endif %}
