{% extends '_layout.volt' %}
{% block body_class %}show404{% endblock %}

{% block title %}Not found{% endblock %}

{% block content %}

    <br /><br />
    <div class="row justify-content-md-center">
        <div class="col-4 offset-sm-4">
            <h1>error/404</h1>
            <h3>means not found !</h3>
        </div>
    </div>

{% endblock %}

{% block footer %}
    <hr noshade="" />
{% endblock %}

{% block javascripts %}
    {{ super() }}
{% endblock %}


