{% extends '_layout.volt' %}
{% block body_class %}welcome{% endblock %}

{% block title %}Welcome !{% endblock %}

{% block content %}
<br><br><br>
    <div class="row justify-content-md-center mt-2">
        <div class="col-6 offset-xs-1">
            Hi there ))) its only a Homepage !<br />
            You can {{ link_to("/login", "login yourself") }}
        </div>
    </div>
{% endblock %}

{% block footer %}
    {#{{ super() }}#}
    <hr noshade="" />
{% endblock %}

{% block javascripts %}
    {{ super() }}
{% endblock %}